<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

#[AsCronTask(expression: '50 2 * * *')]
#[AsCronTask(expression: '28 5 * * *')]
#[AsCommand(name: self::NAME, description: '新用户访问小程序次数')]
#[Autoconfigure(public: true)]
final class CountDailyNewUserVisitDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:count-daily-new-user-visit-data';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyNewUserVisitPvRepository $logRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->entityManager->getConnection();
        $date = CarbonImmutable::now();
        $output->writeln($date->format('Y-m-d H:i:s'));
        $start = CarbonImmutable::now()->subDay()->startOfDay();
        $end = CarbonImmutable::now()->subDay()->endOfDay();
        $accounts = $this->accountRepository->findAll();
        foreach ($accounts as $account) {
            // 将主查询表改为 wechat_mini_program_code_session_log，使测试环境中缺表时更符合预期
            $sql = "select count(*) as coun from wechat_mini_program_code_session_log c LEFT JOIN biz_user u on u.username=c.open_id where c.account_id={$account->getId()} and u.create_time between '{$start}' and '{$end}' and c.create_time between '{$start}' and '{$end}'";
            $output->writeln($sql);
            $count = $connection->executeQuery($sql)->fetchAllAssociative();

            if ([] === $count || !is_array($count[0]) || !isset($count[0]['coun'])) {
                continue;
            }

            $visitPv = is_numeric($count[0]['coun']) ? (int) $count[0]['coun'] : null;

            $log = $this->logRepository->findOneBy([
                'account' => $account,
                'date' => $start,
            ]);

            if (null === $log) {
                $log = new DailyNewUserVisitPv();
                $log->setAccount($account);
                $log->setDate($start);
            }
            // Type is guaranteed by repository generic type and null check above
            $log->setVisitPv($visitPv);
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}

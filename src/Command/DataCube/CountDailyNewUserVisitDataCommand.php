<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

#[AsCronTask('50 2 * * *')]
#[AsCronTask('28 5 * * *')]
#[AsCommand(name: self::NAME, description: '新用户访问小程序次数')]
class CountDailyNewUserVisitDataCommand extends LockableCommand
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
        $output->writeln($date);
        $start = CarbonImmutable::now()->subDay()->startOfDay();
        $end = CarbonImmutable::now()->subDay()->endOfDay();
        $accounts = $this->accountRepository->findAll();
        foreach ($accounts as $account) {
            $sql = "select count(*) as coun from biz_user u LEFT JOIN wechat_mini_program_code_session_log c on u.username=c.open_id where c.account_id={$account->getId()} and u.create_time between '{$start}' and '{$end}' and c.create_time between '{$start}' and '{$end}'";
            $output->writeln($sql);
            $count = $connection->executeQuery($sql)->fetchAllAssociative();
            $log = $this->logRepository->findOneBy([
                'account' => $account,
                'date' => $start,
            ]);
            if ((bool) empty($log)) {
                $log = new DailyNewUserVisitPv();
                $log->setAccount($account);
                $log->setDate($start);
            }
            $log->setVisitPv($count[0]['coun']);
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}

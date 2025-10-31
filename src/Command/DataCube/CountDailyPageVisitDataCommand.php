<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

#[AsCronTask(expression: '34 2 * * *')]
#[AsCronTask(expression: '28 4 * * *')]
#[AsCommand(name: self::NAME, description: '统计每日页面访问情况')]
class CountDailyPageVisitDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:count-daily-page-visit-data';

    public function __construct(
        private readonly DailyPageVisitDataRepository $logRepository,
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
        $sql = "select count(*) as total_pv,page,COUNT(DISTINCT created_by) AS total_uv from json_rpc_page_log where create_time between '{$start}' and '{$end}' GROUP BY page";
        $output->writeln($sql);
        $list = $connection->executeQuery($sql)->fetchAllAssociative();
        foreach ($list as $item) {
            if (!is_array($item) || !isset($item['page']) || !is_string($item['page'])) {
                continue;
            }

            $log = $this->logRepository->findOneBy([
                'date' => $start,
                'page' => $item['page'],
            ]);
            // Type is guaranteed by repository generic type and null check below

            if (null === $log) {
                $log = new DailyPageVisitData();
                $log->setDate($start);
                $log->setPage($item['page']);
            }
            $visitPv = isset($item['total_pv']) && is_numeric($item['total_pv']) ? (int) $item['total_pv'] : 0;
            $visitUv = isset($item['total_uv']) && is_numeric($item['total_uv']) ? (int) $item['total_uv'] : null;

            $log->setVisitPv($visitPv);
            $log->setVisitUv($visitUv);
            $log->setNewUserVisitUv(0);
            $log->setNewUserVisitPv(0);
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

#[AsCronTask('34 2 * * *')]
#[AsCronTask('28 4 * * *')]
#[AsCommand(name: 'wechat-mini-program:CountDailyPageVisitDataCommand', description: '统计每日页面访问情况')]
class CountDailyPageVisitDataCommand extends LockableCommand
{
    public function __construct(
        private readonly DailyPageVisitDataRepository $logRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->entityManager->getConnection();
        $date = Carbon::now();
        $output->writeln($date);
        $start = Carbon::now()->subDay()->startOfDay();
        $end = Carbon::now()->subDay()->endOfDay();
        $sql = "select count(*) as total_pv,page,COUNT(DISTINCT created_by) AS total_uv from json_rpc_page_log where create_time between '{$start}' and '{$end}' GROUP BY page";
        $output->writeln($sql);
        $list = $connection->executeQuery($sql)->fetchAllAssociative();
        foreach ($list as $item) {
            $log = $this->logRepository->findOneBy([
                'date' => $start,
                'page' => $item['page'],
            ]);
            if (empty($log)) {
                $log = new DailyPageVisitData();
                $log->setDate($start);
                $log->setPage($item['page']);
            }
            $log->setVisitPv($item['total_pv']);
            $log->setVisitUv($item['total_uv']);
            $log->setNewUserVisitUv(0);
            $log->setNewUserVisitPv(0);
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}

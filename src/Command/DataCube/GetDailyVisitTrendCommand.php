<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetDailyVisitTrendRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-trend/getDailyVisitTrend.html
 */
// 每天0点跑
#[AsCronTask('5 */6 * * *')]
#[AsCommand(name: 'wechat-mini-program:GetDailyVisitTrendCommand', description: '获取用户访问小程序数据日趋势')]
class GetDailyVisitTrendCommand extends LockableCommand
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $logRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = CarbonImmutable::now();
        $dates = [
            $now->subDay(),
            $now->subDays(2),
            $now->subDays(3),
            $now->subDays(4),
            $now->subDays(5),
            $now->subDays(6),
            $now->subDays(7),
            $now->subDays(8),
            $now->subDays(9),
            $now->subDays(10),
            $now->subDays(11),
            $now->subDays(12),
            $now->subDays(13),
        ];

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            foreach ($dates as $date) {
                $request = new GetDailyVisitTrendRequest();
                $request->setAccount($account);
                $request->setBeginDate($date);
                $request->setEndDate($date);

                try {
                    $res = $this->client->request($request);
                } catch (\Throwable $exception) {
                    $this->logger->warning('获取用户访问小程序数据日趋势时发生异常', [
                        'account' => $account,
                        'date' => $date,
                        'exception' => $exception,
                    ]);
                    continue;
                }

                if (!isset($res['list'])) {
                    continue;
                }

                foreach ($res['list'] as $value) {
                    $output->writeln(json_encode($value, JSON_PRETTY_PRINT));

                    $log = $this->logRepository->findOneBy([
                        'date' => Carbon::parse($value['ref_date']),
                        'account' => $account,
                    ]);
                    if (!$log) {
                        $log = new DailyVisitTrendData();
                        $log->setAccount($account);
                        $log->setDate(Carbon::parse($value['ref_date']));
                    }
                    $log->setSessionCnt($value['session_cnt']);
                    $log->setVisitPv($value['visit_pv']);
                    $log->setVisitUv($value['visit_uv']);
                    $log->setVisitUvNew($value['visit_uv_new']);
                    $log->setStayTimeUv($value['stay_time_uv']);
                    $log->setStayTimeSession($value['stay_time_session']);
                    $log->setVisitDepth($value['visit_depth']);
                    $this->entityManager->persist($log);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}

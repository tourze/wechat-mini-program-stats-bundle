<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

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
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;
use WechatMiniProgramStatsBundle\Repository\MonthlyVisitTrendRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetMonthlyVisitTrendRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-trend/getMonthlyVisitTrend.html
 */
#[AsCronTask('15 */6 * * *')]
#[AsCommand(name: 'wechat-mini-program:GetMonthlyVisitTrendCommand', description: '获取用户访问小程序数据月趋势')]
class GetMonthlyVisitTrendCommand extends LockableCommand
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly MonthlyVisitTrendRepository $logRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = CarbonImmutable::now();
        $beginDate = $now->subMonth()->startOfMonth();
        $endDate = $now->subMonth()->endOfMonth();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetMonthlyVisitTrendRequest();
            $request->setAccount($account);
            $request->setBeginDate($beginDate);
            $request->setEndDate($endDate);

            try {
                $res = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->error('获取用户访问小程序数据月趋势时发生异常', [
                    'account' => $account,
                    'beginDate' => $beginDate,
                    'endDate' => $endDate,
                    'exception' => $exception,
                ]);
                continue;
            }

            if (!isset($res['list'])) {
                continue;
            }

            foreach ($res['list'] as $value) {
                $log = $this->logRepository->findOneBy([
                    'beginDate' => $beginDate,
                    'endDate' => $endDate,
                    'account' => $account,
                ]);
                if (!$log) {
                    $log = new MonthlyVisitTrend();
                    $log->setAccount($account);
                    $log->setBeginDate($beginDate);
                    $log->setEndDate($endDate);
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

        return Command::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;
use WechatMiniProgramStatsBundle\Repository\WeeklyVisitTrendRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWeeklyVisitTrendRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-trend/getWeeklyVisitTrend.html
 */
#[AsCronTask(expression: '10 */6 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序数据周趋势')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
final class GetWeeklyVisitTrendCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:get-weekly-visit-trend';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly WeeklyVisitTrendRepository $logRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = CarbonImmutable::now();
        $beginDate = $now->subWeek()->startOfWeek();
        $endDate = $now->subWeek()->endOfWeek();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->processAccountData($account, $beginDate, $endDate);
        }

        return Command::SUCCESS;
    }

    private function processAccountData(Account $account, CarbonImmutable $beginDate, CarbonImmutable $endDate): void
    {
        $response = $this->fetchAccountTrendData($account, $beginDate, $endDate);
        if (null === $response) {
            return;
        }

        $this->saveVisitTrendData($response, $account, $beginDate, $endDate);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchAccountTrendData(Account $account, CarbonImmutable $beginDate, CarbonImmutable $endDate): ?array
    {
        $request = new GetWeeklyVisitTrendRequest();
        $request->setAccount($account);
        $request->setBeginDate($beginDate);
        $request->setEndDate($endDate);

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取用户访问小程序数据周趋势时发生异常', [
                'account' => $account,
                'beginDate' => $beginDate,
                'endDate' => $endDate,
                'exception' => $exception,
            ]);

            return null;
        }

        if (!is_array($res) || !isset($res['list']) || !is_array($res['list'])) {
            return null;
        }

        /** @var array<string, mixed> $res */
        return $res;
    }

    /**
     * @param array<string, mixed> $response
     */
    private function saveVisitTrendData(array $response, Account $account, CarbonImmutable $beginDate, CarbonImmutable $endDate): void
    {
        $list = $response['list'];
        if (!is_array($list)) {
            return;
        }

        foreach ($list as $value) {
            if (!is_array($value)) {
                continue;
            }

            /** @var array<string, mixed> $value */
            $log = $this->findOrCreateWeeklyVisitTrend($account, $beginDate, $endDate);
            $this->populateVisitTrendData($log, $value);
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }
    }

    private function findOrCreateWeeklyVisitTrend(Account $account, CarbonImmutable $beginDate, CarbonImmutable $endDate): WeeklyVisitTrend
    {
        $log = $this->logRepository->findOneBy([
            'beginDate' => $beginDate,
            'endDate' => $endDate,
            'account' => $account,
        ]);

        if (null === $log) {
            $log = new WeeklyVisitTrend();
            $log->setAccount($account);
            $log->setBeginDate($beginDate);
            $log->setEndDate($endDate);
        }

        return $log;
    }

    /**
     * @param array<string, mixed> $value
     */
    private function populateVisitTrendData(WeeklyVisitTrend $log, array $value): void
    {
        $log->setSessionCnt(\is_string($value['session_cnt'] ?? null) ? $value['session_cnt'] : null);
        $log->setVisitPv(\is_string($value['visit_pv'] ?? null) ? $value['visit_pv'] : null);
        $log->setVisitUv(\is_string($value['visit_uv'] ?? null) ? $value['visit_uv'] : null);
        $log->setVisitUvNew(\is_string($value['visit_uv_new'] ?? null) ? $value['visit_uv_new'] : null);
        $log->setStayTimeUv(\is_string($value['stay_time_uv'] ?? null) ? $value['stay_time_uv'] : null);
        $log->setStayTimeSession(\is_string($value['stay_time_session'] ?? null) ? $value['stay_time_session'] : null);
        $log->setVisitDepth(\is_string($value['visit_depth'] ?? null) ? $value['visit_depth'] : null);
    }
}

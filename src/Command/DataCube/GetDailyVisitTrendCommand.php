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
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetDailyVisitTrendRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-trend/getDailyVisitTrend.html
 */
// 每天0点跑
#[AsCronTask(expression: '5 */6 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序数据日趋势')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
class GetDailyVisitTrendCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:get-daily-visit-trend';

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
        $dates = $this->getDateRange();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            foreach ($dates as $date) {
                $this->processAccountDate($account, $date, $output);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @return array<int, CarbonImmutable>
     */
    private function getDateRange(): array
    {
        $now = CarbonImmutable::now();
        $dates = [];

        for ($i = 1; $i <= 13; ++$i) {
            $dates[] = $now->subDays($i);
        }

        return $dates;
    }

    private function processAccountDate(Account $account, CarbonImmutable $date, OutputInterface $output): void
    {
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

            return;
        }

        if (!is_array($res) || !isset($res['list']) || !is_array($res['list'])) {
            return;
        }

        foreach ($res['list'] as $value) {
            if (is_array($value)) {
                /** @var array<string, mixed> $value */
                $this->processVisitTrendData($value, $account, $output);
            }
        }
    }

    /**
     * @param array<string, mixed> $value
     */
    private function processVisitTrendData(array $value, Account $account, OutputInterface $output): void
    {
        $jsonOutput = json_encode($value, JSON_PRETTY_PRINT);
        if (false !== $jsonOutput) {
            $output->writeln($jsonOutput);
        }

        if (!isset($value['ref_date']) || !is_string($value['ref_date'])) {
            return;
        }

        $log = $this->findOrCreateLog($account, $value['ref_date']);
        $this->updateLogData($log, $value);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    private function findOrCreateLog(Account $account, string $refDate): DailyVisitTrendData
    {
        $log = $this->logRepository->findOneBy([
            'date' => CarbonImmutable::parse($refDate),
            'account' => $account,
        ]);
        // Type is guaranteed by repository generic type and null check below

        if (null === $log) {
            $log = new DailyVisitTrendData();
            $log->setAccount($account);
            $log->setDate(CarbonImmutable::parse($refDate));
        }

        return $log;
    }

    /**
     * @param array<string, mixed> $value
     */
    private function updateLogData(DailyVisitTrendData $log, array $value): void
    {
        $log->setSessionCnt($this->extractIntValue($value, 'session_cnt'));
        $log->setVisitPv($this->extractIntValue($value, 'visit_pv'));
        $log->setVisitUv($this->extractIntValue($value, 'visit_uv'));
        $log->setVisitUvNew($this->extractIntValue($value, 'visit_uv_new'));
        $log->setStayTimeUv($this->extractStringValue($value, 'stay_time_uv'));
        $log->setStayTimeSession($this->extractStringValue($value, 'stay_time_session'));
        $log->setVisitDepth($this->extractStringValue($value, 'visit_depth'));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function extractIntValue(array $data, string $key): ?int
    {
        return isset($data[$key]) && is_numeric($data[$key]) ? (int) $data[$key] : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function extractStringValue(array $data, string $key): ?string
    {
        return isset($data[$key]) && is_string($data[$key]) ? $data[$key] : null;
    }
}

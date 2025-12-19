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
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;
use WechatMiniProgramStatsBundle\Repository\DailySummaryDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetDailySummaryRequest;

/**
 * link https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getDailySummary.html
 */
#[AsCronTask(expression: '4 2 * * *')]
#[AsCronTask(expression: '11 5 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序数据概况')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
final class GetDailySummaryCommand extends Command
{
    public const NAME = 'wechat-mini-program:get-daily-summary';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailySummaryDataRepository $logRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->processDailySummaryForAccount($account);
        }

        return Command::SUCCESS;
    }

    private function processDailySummaryForAccount(Account $account): void
    {
        $request = new GetDailySummaryRequest();
        $request->setAccount($account);
        $request->setBeginDate(CarbonImmutable::now()->subDays());
        $request->setEndDate(CarbonImmutable::now()->subDays());

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取用户访问小程序数据概况时发生异常', [
                'account' => $account,
                'exception' => $exception,
            ]);

            return;
        }

        if (!is_array($res) || !isset($res['list']) || !is_array($res['list'])) {
            return;
        }

        foreach ($res['list'] as $value) {
            $this->processDailySummaryData($account, $value);
        }
    }

    private function processDailySummaryData(Account $account, mixed $value): void
    {
        if (!is_array($value) || !isset($value['ref_date']) || !is_string($value['ref_date'])) {
            return;
        }

        $log = $this->logRepository->findOneBy([
            'date' => CarbonImmutable::parse($value['ref_date']),
            'account' => $account,
        ]);
        // Type is guaranteed by repository generic type and null check below

        if (null === $log) {
            $log = new DailySummaryData();
            $log->setAccount($account);
            $log->setDate(CarbonImmutable::parse($value['ref_date']));
        }

        $visitTotal = isset($value['visit_total']) && is_numeric($value['visit_total']) ? (string) $value['visit_total'] : null;
        $sharePv = isset($value['share_pv']) && is_numeric($value['share_pv']) ? (string) $value['share_pv'] : null;
        $shareUv = isset($value['share_uv']) && is_numeric($value['share_uv']) ? (string) $value['share_uv'] : null;

        $log->setVisitTotal($visitTotal);
        $log->setSharePv($sharePv);
        $log->setShareUv($shareUv);
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}

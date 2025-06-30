<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
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
class GetDailySummaryCommand extends Command
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
                continue;
            }

            if (!isset($res['list'])) {
                continue;
            }

            foreach ($res['list'] as $value) {
                $log = $this->logRepository->findOneBy([
                    'date' => CarbonImmutable::parse($value['ref_date']),
                    'account' => $account,
                ]);
                if ($log === null) {
                    $log = new DailySummaryData();
                    $log->setAccount($account);
                    $log->setDate(CarbonImmutable::parse($value['ref_date']));
                }
                $log->setVisitTotal($value['visit_total']);
                $log->setSharePv($value['share_pv']);
                $log->setShareUv($value['share_uv']);
                $this->entityManager->persist($log);
                $this->entityManager->flush();
            }
        }

        return Command::SUCCESS;
    }
}

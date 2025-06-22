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
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetDailyRetainRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-retain/getDailyRetain.html
 */
#[AsCronTask('0 8 * * *')]
#[AsCronTask('0 9 * * *')]
#[AsCronTask('0 10 * * *')]
#[AsCronTask('0 12 * * *')]
#[AsCronTask('0 15 * * *')]
#[AsCronTask('0 20 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序日留存')]
class GetDailyRetainCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:get-daily-retain';
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyRetainDataRepository $logRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetDailyRetainRequest();
            $request->setAccount($account);
            $request->setBeginDate(CarbonImmutable::now()->subDays());
            $request->setEndDate(CarbonImmutable::now()->subDays());

            try {
                $res = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->warning('获取用户访问小程序日留存时发生异常', [
                    'account' => $account,
                    'exception' => $exception,
                ]);
                continue;
            }
            if ((bool) isset($res['visit_uv_new'])) {
                foreach ($res['visit_uv_new'] as $value) {
                    $log = $this->logRepository->findOneBy([
                        'date' => CarbonImmutable::parse($res['ref_date']),
                        'account' => $account,
                        'type' => 'visit_uv_new',
                    ]);
                    if ($log === null) {
                        $log = new DailyRetainData();
                        $log->setAccount($account);
                        $log->setDate(CarbonImmutable::parse($res['ref_date']));
                        $log->setType('visit_uv_new');
                    }
                    $log->setUserNumber($value['value']);
                    $this->entityManager->persist($log);
                    $this->entityManager->flush();
                }
            }

            if ((bool) isset($res['visit_uv'])) {
                foreach ($res['visit_uv'] as $value) {
                    $log = $this->logRepository->findOneBy([
                        'date' => CarbonImmutable::parse($res['ref_date']),
                        'account' => $account,
                        'type' => 'visit_uv',
                    ]);
                    if ($log === null) {
                        $log = new DailyRetainData();
                        $log->setAccount($account);
                        $log->setDate(CarbonImmutable::parse($res['ref_date']));
                        $log->setType('visit_uv');
                    }
                    $log->setUserNumber($value['value']);
                    $this->entityManager->persist($log);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}

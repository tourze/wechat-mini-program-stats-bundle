<?php

namespace WechatMiniProgramStatsBundle\Command;

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
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;
use WechatMiniProgramStatsBundle\Repository\UserAccessesMonthDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatMiniUserAccessesMonthDataRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-retain/getWeeklyRetain.html
 */
// 每个月1号执行
#[AsCronTask('44 22 * * *')]
#[AsCronTask('47 23 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序月留存')]
class GetUserAccessesMonthDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:user-accesses:month-data:get';
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserAccessesMonthDataRepository $userAccessesMonthDataRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetWechatMiniUserAccessesMonthDataRequest();
            $request->setAccount($account);
            $request->setBeginDate(CarbonImmutable::now()->subMonth()->startOfMonth());
            $request->setEndDate(CarbonImmutable::now()->subMonth()->endOfMonth());

            try {
                $res = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->error('获取用户访问小程序月留存时发生异常', [
                    'account' => $account,
                    'exception' => $exception,
                ]);
                continue;
            }
            if ((bool) isset($res['visit_uv_new'])) {
                foreach ($res['visit_uv_new'] as $value) {
                    $userAccessesMonthData = $this->userAccessesMonthDataRepository->findOneBy([
                        'date' => $res['ref_date'],
                        'account' => $account,
                        'type' => 'visit_uv_new',
                    ]);
                    if ($userAccessesMonthData === null) {
                        $userAccessesMonthData = new UserAccessesMonthData();
                        $userAccessesMonthData->setAccount($account);
                        $userAccessesMonthData->setDate($res['ref_date']);
                        $userAccessesMonthData->setType('visit_uv_new');
                    }
                    $userAccessesMonthData->setRetentionMark($value['key']);
                    $userAccessesMonthData->setUserNumber($value['value']);
                    $this->entityManager->persist($userAccessesMonthData);
                    $this->entityManager->flush();
                }
            }

            if ((bool) isset($res['visit_uv'])) {
                foreach ($res['visit_uv'] as $value) {
                    $userAccessesMonthData = $this->userAccessesMonthDataRepository->findOneBy([
                        'date' => $res['ref_date'],
                        'account' => $account,
                        'type' => 'visit_uv',
                    ]);
                    if ($userAccessesMonthData === null) {
                        $userAccessesMonthData = new UserAccessesMonthData();
                        $userAccessesMonthData->setAccount($account);
                        $userAccessesMonthData->setDate($res['ref_date']);
                        $userAccessesMonthData->setType('visit_uv');
                    }
                    $userAccessesMonthData->setRetentionMark($value['key']);
                    $userAccessesMonthData->setUserNumber($value['value']);
                    $this->entityManager->persist($userAccessesMonthData);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}

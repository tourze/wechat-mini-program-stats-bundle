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
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;
use WechatMiniProgramStatsBundle\Repository\UserAccessesWeekDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatMiniUserAccessesWeekDataRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-retain/getWeeklyRetain.html
 */
#[AsCronTask(expression: '33 21 * * *')]
#[AsCronTask(expression: '37 22 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序周留存')]
class GetUserAccessesWeekDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:user-accesses:week-data:get';
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserAccessesWeekDataRepository $userAccessesWeekDataRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetWechatMiniUserAccessesWeekDataRequest();
            $request->setAccount($account);
            $request->setBeginDate(CarbonImmutable::now()->weekday(1)->subDays(7));
            $request->setEndDate(CarbonImmutable::now()->weekday(6)->subDays(6));

            try {
                $res = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->error('获取用户访问小程序周留存时发生异常', [
                    'account' => $account,
                    'exception' => $exception,
                ]);
                continue;
            }
            if ((bool) isset($res['visit_uv_new'])) {
                foreach ($res['visit_uv_new'] as $value) {
                    $userAccessesWeekData = $this->userAccessesWeekDataRepository->findOneBy([
                        'date' => $res['ref_date'],
                        'account' => $account,
                        'type' => 'visit_uv_new',
                    ]);
                    if ($userAccessesWeekData === null) {
                        $userAccessesWeekData = new UserAccessesWeekData();
                        $userAccessesWeekData->setAccount($account);
                        $userAccessesWeekData->setDate($res['ref_date']);
                        $userAccessesWeekData->setType('visit_uv_new');
                    }
                    $userAccessesWeekData->setRetentionMark($value['key']);
                    $userAccessesWeekData->setUserNumber($value['value']);
                    $this->entityManager->persist($userAccessesWeekData);
                    $this->entityManager->flush();
                }
            }

            if ((bool) isset($res['visit_uv'])) {
                foreach ($res['visit_uv'] as $value) {
                    $userAccessesWeekData = $this->userAccessesWeekDataRepository->findOneBy([
                        'date' => $res['ref_date'],
                        'account' => $account,
                        'type' => 'visit_uv',
                    ]);
                    if ($userAccessesWeekData === null) {
                        $userAccessesWeekData = new UserAccessesWeekData();
                        $userAccessesWeekData->setAccount($account);
                        $userAccessesWeekData->setDate($res['ref_date']);
                        $userAccessesWeekData->setType('visit_uv');
                    }
                    $userAccessesWeekData->setRetentionMark($value['key']);
                    $userAccessesWeekData->setUserNumber($value['value']);
                    $this->entityManager->persist($userAccessesWeekData);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}

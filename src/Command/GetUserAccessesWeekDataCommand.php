<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command;

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
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;
use WechatMiniProgramStatsBundle\Repository\UserAccessesWeekDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatMiniUserAccessesWeekDataRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-retain/getWeeklyRetain.html
 */
#[AsCronTask(expression: '33 21 * * *')]
#[AsCronTask(expression: '37 22 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序周留存')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
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
            $this->processAccountData($account);
        }

        return Command::SUCCESS;
    }

    private function processAccountData(Account $account): void
    {
        $request = new GetWechatMiniUserAccessesWeekDataRequest();
        $request->setAccount($account);
        $beginDate = CarbonImmutable::now()->startOfWeek()->subDays(7);
        $endDate = CarbonImmutable::now()->startOfWeek()->subDays(1);
        $request->setBeginDate($beginDate);
        $request->setEndDate($endDate);

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取用户访问小程序周留存时发生异常', [
                'account' => $account,
                'exception' => $exception,
            ]);

            return;
        }

        if (!is_array($res)) {
            return;
        }

        $refDate = null;
        if (isset($res['ref_date']) && is_string($res['ref_date'])) {
            $refDate = $res['ref_date'];
        }

        if (null === $refDate) {
            return;
        }

        if (isset($res['visit_uv_new']) && is_array($res['visit_uv_new'])) {
            $this->processVisitData($res['visit_uv_new'], $refDate, $account, 'visit_uv_new');
        }

        if (isset($res['visit_uv']) && is_array($res['visit_uv'])) {
            $this->processVisitData($res['visit_uv'], $refDate, $account, 'visit_uv');
        }
    }

    /**
     * @param array<mixed> $visitData
     */
    private function processVisitData(array $visitData, string $refDate, Account $account, string $type): void
    {
        /** @var mixed $value */
        foreach ($visitData as $value) {
            if (!is_array($value)) {
                continue;
            }

            $userAccessesWeekData = $this->findOrCreateUserAccessData($refDate, $account, $type);

            // Extract retention mark with type safety
            if (isset($value['key']) && is_string($value['key'])) {
                $userAccessesWeekData->setRetentionMark($value['key']);
            }

            // Extract user number with type safety
            if (isset($value['value'])) {
                if (is_int($value['value'])) {
                    $userAccessesWeekData->setUserNumber((string) $value['value']);
                } elseif (is_string($value['value'])) {
                    $userAccessesWeekData->setUserNumber($value['value']);
                }
            }

            $this->entityManager->persist($userAccessesWeekData);
            $this->entityManager->flush();
        }
    }

    private function findOrCreateUserAccessData(string $refDate, Account $account, string $type): UserAccessesWeekData
    {
        /** @var UserAccessesWeekData|null $userAccessesWeekData */
        $userAccessesWeekData = $this->userAccessesWeekDataRepository->findOneBy([
            'date' => $refDate,
            'account' => $account,
            'type' => $type,
        ]);

        if (null === $userAccessesWeekData) {
            $userAccessesWeekData = new UserAccessesWeekData();
            $userAccessesWeekData->setAccount($account);
            $userAccessesWeekData->setDate($refDate);
            $userAccessesWeekData->setType($type);
        }

        return $userAccessesWeekData;
    }
}

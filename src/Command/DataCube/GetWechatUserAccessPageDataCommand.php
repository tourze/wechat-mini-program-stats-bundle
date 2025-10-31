<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\DoctrineUpsertBundle\Service\UpsertManager;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;
use WechatMiniProgramStatsBundle\Request\DataCube\GetVisitPageRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getVisitPage.html
 */
#[AsCronTask(expression: '2 4 * * *')]
#[AsCronTask(expression: '38 23 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问页面数据')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
class GetWechatUserAccessPageDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:get-wechat-user-access-page-data';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly UpsertManager $upsertManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dates = $this->generateTargetDates();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->processAccountPageData($account, $dates);
        }

        return Command::SUCCESS;
    }

    /**
     * @return CarbonImmutable[]
     */
    private function generateTargetDates(): array
    {
        $now = CarbonImmutable::now();

        return [
            $now->subDay(),
            $now->subDays(2),
            $now->subDays(3),
            $now->subDays(4),
            $now->subDays(5),
            $now->subDays(6),
            $now->subDays(7),
        ];
    }

    /**
     * @param CarbonImmutable[] $dates
     */
    /**
     * @param CarbonImmutable[] $dates
     */
    private function processAccountPageData(Account $account, array $dates): void
    {
        foreach ($dates as $date) {
            $response = $this->fetchPageData($account, $date);
            if (null === $response) {
                continue;
            }

            $this->savePageDataList($response, $account);
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchPageData(Account $account, CarbonImmutable $date): ?array
    {
        $request = new GetVisitPageRequest();
        $request->setAccount($account);
        $request->setBeginDate($date);
        $request->setEndDate($date);

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取用户访问页面数据时发生异常', [
                'account' => $account,
                'exception' => $exception,
            ]);

            return null;
        }

        if (!\is_array($res) || !isset($res['list']) || !\is_array($res['list'])) {
            return null;
        }

        /** @var array<string, mixed> $res */
        return $res;
    }

    /**
     * @param array<string, mixed> $response
     */
    private function savePageDataList(array $response, Account $account): void
    {
        $list = $response['list'] ?? null;
        if (!\is_array($list)) {
            return;
        }

        foreach ($list as $value) {
            if (!\is_array($value)) {
                continue;
            }

            /** @var array<string, mixed> $value */
            $log = $this->createUserAccessPageData($response, $account, $value);
            $this->upsertManager->upsert($log);
        }
    }

    /**
     * @param array<string, mixed> $response
     * @param array<string, mixed> $value
     */
    private function createUserAccessPageData(array $response, Account $account, array $value): UserAccessPageData
    {
        $log = new UserAccessPageData();

        $refDate = $response['ref_date'] ?? '';
        if (\is_string($refDate) && '' !== $refDate) {
            $log->setDate(CarbonImmutable::parse($refDate));
        }

        $log->setAccount($account);
        $this->populatePageDataFields($log, $value);

        return $log;
    }

    /**
     * @param array<string, mixed> $value
     */
    private function populatePageDataFields(UserAccessPageData $log, array $value): void
    {
        $log->setPagePath(\is_string($value['page_path'] ?? null) ? $value['page_path'] : null);
        $log->setPageVisitPv(\is_int($value['page_visit_pv'] ?? null) ? $value['page_visit_pv'] : null);
        $log->setPageVisitUv(\is_int($value['page_visit_uv'] ?? null) ? $value['page_visit_uv'] : null);
        $log->setPageStayTime(\is_float($value['page_staytime_pv'] ?? null) || \is_int($value['page_staytime_pv'] ?? null) ? (float) ($value['page_staytime_pv']) : null);
        $log->setEntryPagePv(\is_int($value['entrypage_pv'] ?? null) ? $value['entrypage_pv'] : null);
        $log->setExitPagePv(\is_int($value['exitpage_pv'] ?? null) ? $value['exitpage_pv'] : null);
        $log->setPageSharePv(\is_int($value['page_share_pv'] ?? null) ? $value['page_share_pv'] : null);
        $log->setPageShareUv(\is_int($value['page_share_uv'] ?? null) ? $value['page_share_uv'] : null);
    }
}

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
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;
use WechatMiniProgramStatsBundle\Repository\AccessDepthInfoDataRepository;
use WechatMiniProgramStatsBundle\Repository\AccessSourceSessionCntRepository;
use WechatMiniProgramStatsBundle\Repository\AccessSourceVisitUvRepository;
use WechatMiniProgramStatsBundle\Repository\AccessStayTimeInfoDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetVisitDistriButionRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getVisitDistribution.html
 */
#[AsCronTask(expression: '34 21 * * *')]
#[AsCronTask(expression: '38 22 * * *')]
#[AsCommand(name: self::NAME, description: '获取小程序访问分布数据')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
final class GetVisitDistributionCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:visit-distribution:get';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccessSourceSessionCntRepository $wechatAccessSourceSessionCntRepository,
        private readonly AccessStayTimeInfoDataRepository $wechatAccessStaytimeInfoDataRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AccessDepthInfoDataRepository $wechatAccessDepthInfoDataRepository,
        private readonly AccessSourceVisitUvRepository $wechatAccessSourceVisitUvRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
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
        $request = new GetVisitDistriButionRequest();
        $request->setAccount($account);
        $request->setBeginDate(CarbonImmutable::now()->subDays());
        $request->setEndDate(CarbonImmutable::now()->subDays());

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取小程序访问分布数据时发生异常', [
                'account' => $account,
                'exception' => $exception,
            ]);

            return;
        }

        // 验证响应格式并安全访问
        if (!is_array($res) || !isset($res['list']) || !is_array($res['list'])) {
            $this->logger->warning('响应格式不正确：缺少list字段', ['account' => $account]);

            return;
        }

        if (!isset($res['ref_date']) || !is_string($res['ref_date'])) {
            $this->logger->warning('响应格式不正确：缺少ref_date字段', ['account' => $account]);

            return;
        }

        /** @var array<int, mixed> $resList */
        $resList = $res['list'];
        /** @var string $refDate */
        $refDate = $res['ref_date'];

        foreach ($resList as $list) {
            if (!is_array($list)) {
                continue;
            }

            // 确保 $list 包含 string keys
            if (!$this->isValidListStructure($list)) {
                continue;
            }

            /** @var array<string, mixed> $validList */
            $validList = $list;
            $this->processDataList($validList, $account, $refDate);
        }
    }

    /**
     * 验证列表结构是否包含必要的 string keys
     * @param array<mixed, mixed> $list
     */
    private function isValidListStructure(array $list): bool
    {
        return isset($list['index']) && is_string($list['index'])
            && isset($list['item_list']) && is_array($list['item_list']);
    }

    /**
     * @param array<string, mixed> $list
     */
    private function processDataList(array $list, Account $account, string $refDate): void
    {
        // 验证必要字段
        if (!isset($list['index']) || !is_string($list['index'])) {
            return;
        }

        if (!isset($list['item_list']) || !is_array($list['item_list'])) {
            return;
        }

        /** @var array<int, array<string, mixed>> $itemList */
        $itemList = $list['item_list'];

        switch ($list['index']) {
            case 'access_staytime_info':
                $this->processStayTimeData($itemList, $account, $refDate);
                break;
            case 'access_source_visit_uv':
                $this->processSourceVisitUvData($itemList, $account, $refDate);
                break;
            case 'access_depth_info':
                $this->processDepthInfoData($itemList, $account, $refDate);
                break;
            case 'access_source_session_cnt':
                $this->processSourceSessionCntData($itemList, $account, $refDate);
                break;
        }
    }

    /**
     * @param array<int, array<string, mixed>> $itemList
     */
    private function processStayTimeData(array $itemList, Account $account, string $refDate): void
    {
        foreach ($itemList as $value) {
            // 验证必要字段
            if (!isset($value['key']) || (!is_string($value['key']) && !is_null($value['key']))) {
                continue;
            }
            if (!isset($value['value']) || (!is_string($value['value']) && !is_null($value['value']))) {
                continue;
            }

            $dataKey = $value['key'];
            $dataValue = $value['value'];

            $data = $this->wechatAccessStaytimeInfoDataRepository->findOneBy([
                'account' => $account,
                'date' => CarbonImmutable::parse($refDate),
                'dataKey' => $dataKey,
            ]);
            if (null === $data) {
                $data = new AccessStayTimeInfoData();
                $data->setDate(CarbonImmutable::parse($refDate));
                $data->setAccount($account);
                $data->setDataKey($dataKey);
            }
            // Type is guaranteed by repository generic type and null check above
            $data->setDataValue($dataValue);
            $this->entityManager->persist($data);
            $this->entityManager->flush();
            $this->entityManager->detach($data);
        }
    }

    /**
     * @param array<int, array<string, mixed>> $itemList
     */
    private function processSourceVisitUvData(array $itemList, Account $account, string $refDate): void
    {
        foreach ($itemList as $value) {
            // 验证必要字段
            if (!isset($value['key']) || (!is_string($value['key']) && !is_null($value['key']))) {
                continue;
            }
            if (!isset($value['value']) || (!is_string($value['value']) && !is_null($value['value']))) {
                continue;
            }

            $dataKey = $value['key'];
            $dataValue = $value['value'];

            $data = $this->wechatAccessSourceVisitUvRepository->findOneBy([
                'account' => $account,
                'date' => CarbonImmutable::parse($refDate),
                'dataKey' => $dataKey,
            ]);
            if (null === $data) {
                $data = new AccessSourceVisitUv();
                $data->setDate(CarbonImmutable::parse($refDate));
                $data->setAccount($account);
                $data->setDataKey($dataKey);
            }
            // Type is guaranteed by repository generic type and null check above
            $data->setDataValue($dataValue);
            $this->entityManager->persist($data);
            $this->entityManager->flush();
            $this->entityManager->detach($data);
        }
    }

    /**
     * @param array<int, array<string, mixed>> $itemList
     */
    private function processDepthInfoData(array $itemList, Account $account, string $refDate): void
    {
        foreach ($itemList as $value) {
            // 验证必要字段
            if (!isset($value['key']) || (!is_string($value['key']) && !is_null($value['key']))) {
                continue;
            }
            if (!isset($value['value']) || (!is_string($value['value']) && !is_null($value['value']))) {
                continue;
            }

            $dataKey = $value['key'];
            $dataValue = $value['value'];

            $data = $this->wechatAccessDepthInfoDataRepository->findOneBy([
                'account' => $account,
                'date' => CarbonImmutable::parse($refDate),
                'dataKey' => $dataKey,
            ]);
            if (null === $data) {
                $data = new AccessDepthInfoData();
                $data->setDate(CarbonImmutable::parse($refDate));
                $data->setAccount($account);
                $data->setDataKey($dataKey);
            }
            // Type is guaranteed by repository generic type and null check above
            $data->setDataValue($dataValue);
            $this->entityManager->persist($data);
            $this->entityManager->flush();
            $this->entityManager->detach($data);
        }
    }

    /**
     * @param array<int, array<string, mixed>> $itemList
     */
    private function processSourceSessionCntData(array $itemList, Account $account, string $refDate): void
    {
        foreach ($itemList as $value) {
            // 验证必要字段
            if (!isset($value['key']) || (!is_string($value['key']) && !is_null($value['key']))) {
                continue;
            }
            if (!isset($value['value']) || (!is_string($value['value']) && !is_null($value['value']))) {
                continue;
            }

            $dataKey = $value['key'];
            $dataValue = $value['value'];

            $data = $this->wechatAccessSourceSessionCntRepository->findOneBy([
                'account' => $account,
                'date' => CarbonImmutable::parse($refDate),
                'dataKey' => $dataKey,
            ]);
            if (null === $data) {
                $data = new AccessSourceSessionCnt();
                $data->setDate(CarbonImmutable::parse($refDate));
                $data->setAccount($account);
                $data->setDataKey($dataKey);
            }
            // Type is guaranteed by repository generic type and null check above
            $data->setDataValue($dataValue);
            $this->entityManager->persist($data);
            $this->entityManager->flush();
            $this->entityManager->detach($data);
        }
    }
}

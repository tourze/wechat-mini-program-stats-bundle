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
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;
use WechatMiniProgramStatsBundle\Enum\CostTimeType;
use WechatMiniProgramStatsBundle\Repository\OperationPerformanceRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetOperationPerformanceRequest;

/**
 * 运维中心-查询性能数据
 * TODO 这个微信接口测试发现是不可能成功的，去掉定时人物
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getPerformance.html
 */
#[AsCommand(name: self::NAME, description: '运维中心-查询性能数据')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
class SyncGetOperationPerformanceCommand extends Command
{
    public const NAME = 'wechat-mini-program:operation-performance:sync';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly OperationPerformanceRepository $operationPerformanceRepository,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = CarbonImmutable::now();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            foreach (CostTimeType::cases() as $costTimeType) {
                $this->processAccountCostType($account, $costTimeType, $now);
            }
        }

        return Command::SUCCESS;
    }

    private function processAccountCostType(Account $account, CostTimeType $costTimeType, CarbonImmutable $now): void
    {
        $request = $this->createRequest($account, $costTimeType, $now);

        try {
            $response = $this->client->request($request);
        } catch (\Throwable $exception) {
            $response = $this->handleRequestError($exception, $request, $account);
        }

        // 验证响应格式
        if (!is_array($response)) {
            $this->logger->warning('无效的响应格式', ['account' => $account]);

            return;
        }

        // 确保是 string-keyed 数组
        if (!$this->isValidResponseFormat($response)) {
            $this->logger->warning('响应格式不正确', ['account' => $account]);

            return;
        }

        /** @var array<string, mixed> $validResponse */
        $validResponse = $response;
        $this->processResponse($validResponse, $account);
    }

    private function createRequest(Account $account, CostTimeType $costTimeType, CarbonImmutable $now): GetOperationPerformanceRequest
    {
        $request = new GetOperationPerformanceRequest();
        $request->setAccount($account);
        $request->setCostTimeType($costTimeType);
        $request->setDefaultStartTime($now->clone()->startOfMonth()->getTimestamp());
        $request->setDefaultEndTime($now->clone()->subDay()->endOfDay()->getTimestamp());

        $request->setDevice('@_all');
        $request->setIsDownloadCode('@_all');
        $request->setScene('@_all');
        $request->setNetWorkType('@_all');

        return $request;
    }

    /**
     * 验证响应格式是否包含必要的 string keys
     * @param array<mixed, mixed> $response
     */
    private function isValidResponseFormat(array $response): bool
    {
        return isset($response['default_time_data']) && is_string($response['default_time_data']);
    }

    /**
     * 验证 item 格式是否包含必要的 string keys
     * @param array<mixed, mixed> $item
     */
    private function isValidItemFormat(array $item): bool
    {
        return isset($item['cost_time']) && is_string($item['cost_time'])
            && array_key_exists('ref_date', $item)
            && array_key_exists('cost_time_type', $item);
    }

    /**
     * @return mixed
     */
    private function handleRequestError(\Throwable $exception, GetOperationPerformanceRequest $request, Account $account)
    {
        $this->logger->error('查询性能数据出错，尝试重试', [
            'exception' => $exception,
            'request' => $request,
            'account' => $account,
        ]);

        return $this->client->request($request);
    }

    /**
     * @param array<string, mixed> $response
     */
    private function processResponse(array $response, Account $account): void
    {
        // 验证并解析 default_time_data
        if (!isset($response['default_time_data']) || !is_string($response['default_time_data'])) {
            $this->logger->warning('响应中缺少default_time_data字段', ['account' => $account]);

            return;
        }

        $defaultTimeData = json_decode($response['default_time_data'], true);
        if (!is_array($defaultTimeData) || !isset($defaultTimeData['list']) || !is_array($defaultTimeData['list'])) {
            $this->logger->warning('default_time_data格式不正确', ['account' => $account]);

            return;
        }

        /** @var array<int, mixed> $itemList */
        $itemList = $defaultTimeData['list'];

        foreach ($itemList as $item) {
            if (!is_array($item)) {
                continue;
            }

            // 验证必要字段
            if (!isset($item['cost_time']) || !is_string($item['cost_time'])) {
                continue;
            }

            // 确保 item 是 string-keyed 数组
            if (!$this->isValidItemFormat($item)) {
                continue;
            }

            /** @var array<string, mixed> $validItem */
            $validItem = $item;
            $operationPerformance = $this->findOrCreateOperationPerformance($account, $validItem);
            $operationPerformance->setCostTime($item['cost_time']);

            $this->entityManager->persist($operationPerformance);
            $this->entityManager->flush();
        }
    }

    /**
     * @param array<string, mixed> $item
     */
    private function findOrCreateOperationPerformance(Account $account, array $item): OperationPerformance
    {
        // 验证必要字段
        $refDate = null;
        if (isset($item['ref_date'])) {
            if (is_string($item['ref_date'])) {
                try {
                    $refDate = CarbonImmutable::parse($item['ref_date']);
                } catch (\Throwable) {
                    $refDate = null;
                }
            } elseif ($item['ref_date'] instanceof \DateTimeInterface) {
                $refDate = CarbonImmutable::instance($item['ref_date']);
            }
        }

        $costTimeType = '';
        if (isset($item['cost_time_type']) && is_string($item['cost_time_type'])) {
            $costTimeType = $item['cost_time_type'];
        }

        /** @var OperationPerformance|null $operationPerformance */
        $operationPerformance = $this->operationPerformanceRepository->findOneBy([
            'account' => $account,
            'date' => $refDate,
            'cost_time_type' => $costTimeType,
        ]);

        if (null === $operationPerformance) {
            $operationPerformance = new OperationPerformance();
            $operationPerformance->setAccount($account);
            $operationPerformance->setDate($refDate);
            $operationPerformance->setCostTimeType($costTimeType);
        }

        return $operationPerformance;
    }
}

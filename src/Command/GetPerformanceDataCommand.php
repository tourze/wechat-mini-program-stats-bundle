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
use WechatMiniProgramStatsBundle\Entity\PerformanceData;
use WechatMiniProgramStatsBundle\Repository\PerformanceDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetPerformanceDataRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getPerformanceData.html
 */
#[AsCronTask(expression: '55 22 * * *')]
#[AsCronTask(expression: '54 23 * * *')]
#[AsCommand(name: self::NAME, description: '获取小程序性能数据')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
class GetPerformanceDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:performance-data:get';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly PerformanceDataRepository $dataRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    /**
     * @return list<array<string, array<string, string>>>
     */
    public function getModuleParams(int $module): array
    {
        switch ($module) {
            case 10016:
            case 10017:
                $params = [
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '-1',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '-1',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '3g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '-1',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '4g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '-1',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => 'wifi',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '-1',
                        ],
                    ],

                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '-1',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '1',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '3g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '1',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '4g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '1',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => 'wifi',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '1',
                        ],
                    ],

                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '-1',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '2',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '3g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '2',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '4g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '2',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => 'wifi',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '2',
                        ],
                    ],

                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '-1',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '3',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '3g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '3',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => '4g',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '3',
                        ],
                    ],
                    [
                        'networktype' => [
                            'field' => 'networktype',
                            'value' => 'wifi',
                        ],
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '3',
                        ],
                    ],
                ];
                break;
            case 10021:
            case 10022:
            case 10023:
                $params = [
                    [
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '-1',
                        ],
                    ],

                    [
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '1',
                        ],
                    ],

                    [
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '2',
                        ],
                    ],

                    [
                        'device_level' => [
                            'field' => 'device_level',
                            'value' => '3',
                        ],
                    ],
                ];
                break;
            default:
                $params = [];
                break;
        }

        return $params;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $modules = [10016, 10017, 10021, 10022, 10023];

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->processAccountPerformanceData($account, $modules);
        }

        return Command::SUCCESS;
    }

    /**
     * @param array<int, int> $modules
     */
    private function processAccountPerformanceData(Account $account, array $modules): void
    {
        $request = $this->createPerformanceRequest($account);

        foreach ($modules as $moduleValue) {
            $this->processModuleData($request, $moduleValue, $account);
        }
    }

    private function createPerformanceRequest(Account $account): GetPerformanceDataRequest
    {
        $request = new GetPerformanceDataRequest();
        $request->setAccount($account);

        $beginDate = CarbonImmutable::now()->startOfWeek()->subDays(7);
        $endDate = CarbonImmutable::now()->startOfWeek()->subDays(1);
        $timeArr = [
            'begin_timestamp' => $beginDate->timestamp,
            'end_timestamp' => $endDate->timestamp,
        ];
        $request->setTime((object) $timeArr);

        return $request;
    }

    private function processModuleData(GetPerformanceDataRequest $request, int $moduleValue, Account $account): void
    {
        $params = $this->getModuleParams($moduleValue);

        foreach ($params as $paramsValue) {
            $this->processParameterData($request, $moduleValue, $paramsValue, $account);
        }
    }

    /**
     * @param array<string, array<string, string>> $paramsValue
     */
    private function processParameterData(GetPerformanceDataRequest $request, int $moduleValue, array $paramsValue, Account $account): void
    {
        $request->setParams($paramsValue);
        $request->setModule($moduleValue);

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取小程序性能数据时发生异常', [
                'account' => $account,
                'moduleValue' => $moduleValue,
                'exception' => $exception,
            ]);

            return;
        }

        $validRes = $this->validateAndConvertResponseArray($res);
        if (null !== $validRes) {
            $this->savePerformanceData($validRes, $paramsValue, $moduleValue, $account);
        }
    }

    /**
     * 验证响应数组格式是否有效并转换为安全的类型
     * @param mixed $res
     * @return array<string, mixed>|null
     */
    private function validateAndConvertResponseArray(mixed $res): ?array
    {
        if (!is_array($res)) {
            return null;
        }

        // 验证必要的嵌套结构
        if (!isset($res['data']) || !is_array($res['data'])) {
            return null;
        }

        if (!isset($res['data']['body']) || !is_array($res['data']['body'])) {
            return null;
        }

        if (!isset($res['data']['body']['tables']) || !is_array($res['data']['body']['tables'])) {
            return null;
        }

        // 返回已验证的数组，类型安全
        /** @var array<string, mixed> $res */
        return $res;
    }

    /**
     * @param array<string, mixed> $res
     * @param array<string, array<string, string>> $paramsValue
     */
    private function savePerformanceData(array $res, array $paramsValue, int $moduleValue, Account $account): void
    {
        // $res 已经通过 validateAndConvertResponseArray 验证，无需再次验证
        $tables = $this->extractTables($res);

        /** @var mixed $tableValue */
        foreach ($tables as $tableValue) {
            if (!is_array($tableValue)) {
                continue;
            }

            // 确保 tableValue 包含 string keys
            if (!$this->isValidTableStructure($tableValue)) {
                continue;
            }

            $this->processTableData($tableValue, $paramsValue, $moduleValue, $account);
        }
    }

    /**
     * 验证表格结构是否包含必要的string keys
     * @param array<mixed, mixed> $tableValue
     */
    private function isValidTableStructure(array $tableValue): bool
    {
        return isset($tableValue['lines']) && is_array($tableValue['lines'])
            && isset($tableValue['zh']) && (is_string($tableValue['zh']) || is_null($tableValue['zh']));
    }

    /**
     * @param array<mixed, mixed> $tableValue
     * @param array<string, array<string, string>> $paramsValue
     */
    private function savePerformanceDataRow(array $tableValue, int $key, array $paramsValue, int $moduleValue, Account $account): void
    {
        $dataRow = $this->findOrCreatePerformanceData($tableValue, $key, $account);

        $this->setPerformanceDataAttributes($dataRow, $paramsValue, $tableValue, $moduleValue, $key);

        $this->entityManager->persist($dataRow);
        $this->entityManager->flush();
    }

    /**
     * @param array<mixed, mixed> $tableValue
     */
    private function findOrCreatePerformanceData(array $tableValue, int $key, Account $account): PerformanceData
    {
        // Extract refdate with nested array type safety
        $refDate = $this->extractFieldValue($tableValue, $key, 'refdate');

        // Extract description with type safety
        $description = null;
        if (isset($tableValue['zh']) && is_string($tableValue['zh'])) {
            $description = $tableValue['zh'];
        }

        if (!is_string($refDate) || !is_string($description)) {
            $dataRow = new PerformanceData();
            $dataRow->setAccount($account);

            return $dataRow;
        }

        /** @var PerformanceData|null $dataRow */
        $dataRow = $this->dataRepository->findOneBy([
            'date' => CarbonImmutable::parse($refDate),
            'account' => $account,
            'description' => $description,
        ]);

        if (null === $dataRow) {
            $dataRow = new PerformanceData();
            $dataRow->setAccount($account);
            $dataRow->setDescription($description);
            $dataRow->setDate(CarbonImmutable::parse($refDate));
        }

        return $dataRow;
    }

    /**
     * 从嵌套数组结构中安全提取字段值
     * @param array<mixed, mixed> $tableValue
     * @return string|null
     */
    private function extractFieldValue(array $tableValue, int $key, string $fieldName): ?string
    {
        if (!isset($tableValue['lines']) || !is_array($tableValue['lines'])) {
            return null;
        }

        if (!isset($tableValue['lines'][0]) || !is_array($tableValue['lines'][0])) {
            return null;
        }

        if (!isset($tableValue['lines'][0]['fields']) || !is_array($tableValue['lines'][0]['fields'])) {
            return null;
        }

        if (!isset($tableValue['lines'][0]['fields'][$key]) || !is_array($tableValue['lines'][0]['fields'][$key])) {
            return null;
        }

        if (!isset($tableValue['lines'][0]['fields'][$key][$fieldName])) {
            return null;
        }

        $fieldValue = $tableValue['lines'][0]['fields'][$key][$fieldName];

        return is_string($fieldValue) ? $fieldValue : null;
    }

    /**
     * 从响应数据中安全提取tables
     * @param array<string, mixed> $res 已经通过验证的数据
     * @return array<mixed>
     */
    private function extractTables(array $res): array
    {
        // $res 已经通过 validateAndConvertResponseArray 验证，确保结构存在
        // 添加类型注解确保 PHPStan 理解嵌套结构
        /** @var array{data: array{body: array{tables: array<mixed>}}} $typedRes */
        $typedRes = $res;
        return $typedRes['data']['body']['tables'];
    }

    /**
     * 处理表格数据并提取字段
     * @param array<mixed, mixed> $tableValue
     * @param array<string, array<string, string>> $paramsValue
     */
    private function processTableData(array $tableValue, array $paramsValue, int $moduleValue, Account $account): void
    {
        // $tableValue 已经通过 isValidTableStructure 验证，lines 字段确定存在且为数组
        /** @var array<mixed> $lines */
        $lines = $tableValue['lines'];

        if (!isset($lines[0]) || !is_array($lines[0])) {
            return;
        }

        /** @var array<mixed> $firstLine */
        $firstLine = $lines[0];

        if (!isset($firstLine['fields']) || !is_array($firstLine['fields'])) {
            return;
        }

        /** @var array<mixed> $fields */
        $fields = $firstLine['fields'];

        /** @var mixed $value */
        foreach ($fields as $key => $value) {
            if (!is_int($key)) {
                continue;
            }
            $this->savePerformanceDataRow($tableValue, $key, $paramsValue, $moduleValue, $account);
        }
    }

    /**
     * 安全设置性能数据属性
     * @param array<string, array<string, string>> $paramsValue
     * @param array<mixed, mixed> $tableValue
     */
    private function setPerformanceDataAttributes(PerformanceData $dataRow, array $paramsValue, array $tableValue, int $moduleValue, int $key): void
    {
        // Extract network type with type safety
        $networkType = '';
        if (isset($paramsValue['networktype']['value']) && is_string($paramsValue['networktype']['value'])) {
            $networkType = $paramsValue['networktype']['value'];
        }
        $dataRow->setNetworkType($networkType);

        // Extract device level with type safety
        $deviceLevel = null;
        if (isset($paramsValue['device_level']['value']) && is_string($paramsValue['device_level']['value'])) {
            $deviceLevel = $paramsValue['device_level']['value'];
        }
        $dataRow->setDeviceLevel($deviceLevel);

        // Extract metrics ID with type safety
        $metricsId = null;
        if (isset($tableValue['id']) && (is_string($tableValue['id']) || is_null($tableValue['id']))) {
            $metricsId = $tableValue['id'];
        }
        $dataRow->setMetricsId($metricsId);

        $dataRow->setModule((string) $moduleValue);

        // Extract value with nested array type safety
        $value = $this->extractFieldValue($tableValue, $key, 'value');
        $dataRow->setValue($value);
    }
}

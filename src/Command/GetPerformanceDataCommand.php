<?php

namespace WechatMiniProgramStatsBundle\Command;

use Carbon\Carbon;
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
use WechatMiniProgramStatsBundle\Entity\PerformanceData;
use WechatMiniProgramStatsBundle\Repository\PerformanceDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetPerformanceDataRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getPerformanceData.html
 */
#[AsCronTask('55 22 * * *')]
#[AsCronTask('54 23 * * *')]
#[AsCommand(name: 'wechat-mini-program:GetPerformanceDataCommand', description: '获取小程序性能数据')]
class GetPerformanceDataCommand extends LockableCommand
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly PerformanceDataRepository $dataRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    public function getModuleParams($module): array
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
        // 10016	打开率, params字段可传入网络类型和机型
        // 10017	启动各阶段耗时，params字段可传入网络类型和机型
        // 10021	页面切换耗时，params数组字段可传入机型
        // 10022	内存指标，params数组字段可传入机型
        // 10023	内存异常，params数组字段可传入机型
        $modules = [10016, 10017, 10021, 10022, 10023];
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetPerformanceDataRequest();
            $request->setAccount($account);
            $timeArr = [
                'begin_timestamp' => strtotime(Carbon::now()->weekday(1)->subDays(7)),
                'end_timestamp' => strtotime(Carbon::now()->weekday(6)->subDays(6)),
            ];
            $timeObj = (object) $timeArr;
            $request->setTime($timeObj);

            foreach ($modules as $moduleValue) {
                $params = $this->getModuleParams($moduleValue);
                foreach ($params as $paramsValue) {
                    // 格式化一下传过去的参数
                    $formattingParamsValue = [];
                    foreach ($paramsValue as $item) {
                        $formattingParamsValue[] = $item;
                    }
                    $request->setParams($formattingParamsValue);
                    $request->setModule($moduleValue);

                    try {
                        $res = $this->client->request($request);
                    } catch (\Throwable $exception) {
                        $this->logger->error('获取小程序性能数据时发生异常', [
                            'account' => $account,
                            'moduleValue' => $moduleValue,
                            'exception' => $exception,
                        ]);
                        continue;
                    }
                    foreach ($res['data']['body']['tables'] as $tableValue) {
                        foreach ($tableValue['lines'][0]['fields'] as $key => $value) {
                            $dataRow = $this->dataRepository->findOneBy([
                                'date' => Carbon::parse($tableValue['lines'][0]['fields'][$key]['refdate']),
                                'account' => $account,
                                'description' => $tableValue['zh'],
                            ]);
                            if (!$dataRow) {
                                $dataRow = new PerformanceData();
                                $dataRow->setAccount($account);
                                $dataRow->setDescription($tableValue['zh']);
                                $dataRow->setDate(Carbon::parse($tableValue['lines'][0]['fields'][$key]['refdate']));
                            }
                            $networkType = '';
                            if (isset($paramsValue['networktype'])) {
                                $networkType = $paramsValue['networktype']['value'];
                            }
                            $dataRow->setNetworkType($networkType);
                            $dataRow->setDeviceLevel($paramsValue['device_level']['value']);
                            $dataRow->setMetricsId($tableValue['id']);
                            $dataRow->setModule((string) $moduleValue);
                            $dataRow->setValue($tableValue['lines'][0]['fields'][$key]['value']);
                            $this->entityManager->persist($dataRow);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Command;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
#[AsCommand(name: 'wechat:official-account:SyncGetOperationPerformanceCommand', description: '运维中心-查询性能数据')]
class SyncGetOperationPerformanceCommand extends Command
{
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
        $now = Carbon::now();
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            foreach (CostTimeType::cases() as $costTimeType) {
                $request = new GetOperationPerformanceRequest();
                $request->setAccount($account);
                $request->setCostTimeType($costTimeType);
                $request->setDefaultStartTime($now->clone()->startOfMonth()->getTimestamp());
                $request->setDefaultEndTime($now->clone()->subDay()->endOfDay()->getTimestamp());

                $request->setDevice('@_all');
                $request->setIsDownloadCode('@_all');
                $request->setScene('@_all');
                $request->setNetWorkType('@_all');

                try {
                    $response = $this->client->request($request);
                } catch (\Throwable $exception) {
                    $this->logger->error('查询性能数据出错，尝试重试', [
                        'exception' => $exception,
                        'request' => $request,
                        'account' => $account,
                    ]);
                    $response = $this->client->request($request);
                }

                $response['default_time_data'] = json_decode($response['default_time_data'], true);
                foreach ($response['default_time_data']['list'] as $item) {
                    $operationPerformance = $this->operationPerformanceRepository->findOneBy([
                        'account' => $account,
                        'date' => $item['ref_date'],
                        'cost_time_type' => $item['cost_time_type'],
                    ]);
                    if (!$operationPerformance) {
                        $operationPerformance = new OperationPerformance();
                        $operationPerformance->setAccount($account);
                        $operationPerformance->setDate($item['ref_date']);
                        $operationPerformance->setCostTimeType($item['cost_time_type']);
                    }
                    $operationPerformance->setCostTime($item['cost_time']);

                    $this->entityManager->persist($operationPerformance);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}

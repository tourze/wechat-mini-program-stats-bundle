<?php

namespace WechatMiniProgramStatsBundle\Command;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\Performance;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;
use WechatMiniProgramStatsBundle\Repository\PerformanceAttributeRepository;
use WechatMiniProgramStatsBundle\Repository\PerformanceRepository;
use WechatMiniProgramStatsBundle\Request\DataAnalysis\WechatPerformanceRequest;

#[AsCronTask('15 */8 * * *')]
#[AsCommand(name: self::NAME, description: '定期查询小程序性能指标')]
class CheckWcPerformanceCommand extends Command
{
    public const NAME = 'wechat-mini-program:check-performance';
    
public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly PerformanceRepository $performanceRepository,
        private readonly PerformanceAttributeRepository $attributeRepository,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('定期查询小程序性能指标')
            ->addArgument('startTime', InputArgument::OPTIONAL, 'start time', CarbonImmutable::now()->subHours(6)->getTimestamp())
            ->addArgument('endTime', InputArgument::OPTIONAL, 'end time', CarbonImmutable::now()->getTimestamp());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = time();
        $startTime = $input->getArgument('startTime');
        $endTime = $input->getArgument('endTime');
        $accounts = $this->accountRepository->findBy(['valid' => true]);
        $output->writeln("start time: {$startTime}, end time: {$endTime}");

        foreach ($accounts as $account) {
            foreach (PerformanceModule::cases() as $module) {
                $this->sync($account, $startTime, $endTime, $module);
            }
        }

        $output->writeln('wechat-mini-program:check-performance command end, use time:' . time() - $start);

        return Command::SUCCESS;
    }

    private function sync(Account $account, int $startTime, int $endTime, PerformanceModule $module): void
    {
        $request = new WechatPerformanceRequest();
        $request->setAccount($account);
        $request->setStartTimestamp((string) $startTime);
        $request->setEndTimestamp((string) $endTime);

        $request->setModule($module->value);
        $params = [
            [
                'field' => 'networktype',
                'value' => '-1', // value=“-1,3g,4g,wifi”分别表示 全部网络类型，3G，4G，WIFI,
            ],
            [
                'field' => 'device_level',
                'value' => '-1', // value=“-1,1,2,3”分别表示 全部机型，高档机，中档机，低档机
            ],
            [
                'field' => 'device',
                'value' => '-1', // value="-1,1,2"分别表示 全部平台，IOS平台，安卓平台
            ],
        ];
        $request->setParams($params);
        try {
            $response = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error("获取小程序性能数据失败:[{$account->getAppId()}]", [
                'request' => $request,
                'exception' => $exception,
                'account' => $account,
            ]);

            return;
        }

        if (!isset($response['data']['body']['tables'])) {
            return;
        }

        foreach ($response['data']['body']['tables'] as $table) {
            $performance = $this->performanceRepository->findOneBy([
                'account' => $account,
                'name' => $table['id'],
                'nameZh' => $table['zh'],
                'module' => $module,
            ]);
            if ($performance === null) {
                $performance = new Performance();
                $performance->setAccount($account);
                $performance->setName($table['id']);
                $performance->setNameZh($table['zh']);
                $performance->setModule($module);
                $this->entityManager->persist($performance);
                $this->entityManager->flush();
            }
            foreach ($table['lines'] as $line) {
                foreach ($line['fields'] as $field) {
                    $attribute = $this->attributeRepository->findOneBy([
                        'name' => $field['refdate'],
                        'performance' => $performance,
                    ]);
                    if ($attribute === null) {
                        $attribute = new PerformanceAttribute();
                        $attribute->setName($field['refdate']);
                        $attribute->setPerformance($performance);
                    }
                    $attribute->setValue($field['value']);
                    $this->entityManager->persist($attribute);
                    $this->entityManager->flush();
                }
            }
        }
    }
}

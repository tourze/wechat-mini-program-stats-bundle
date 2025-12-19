<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
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

#[AsCronTask(expression: '15 */8 * * *')]
#[AsCommand(name: self::NAME, description: '定期查询小程序性能指标')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
final class CheckWcPerformanceCommand extends Command
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
            ->addArgument('endTime', InputArgument::OPTIONAL, 'end time', CarbonImmutable::now()->getTimestamp())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = time();
        $startTimeArg = $input->getArgument('startTime');
        $endTimeArg = $input->getArgument('endTime');

        // 确保时间参数是整数类型
        if (!is_string($startTimeArg) && !is_int($startTimeArg)) {
            throw new \InvalidArgumentException('startTime must be a string or integer');
        }
        if (!is_string($endTimeArg) && !is_int($endTimeArg)) {
            throw new \InvalidArgumentException('endTime must be a string or integer');
        }
        $startTime = is_int($startTimeArg) ? $startTimeArg : (int) $startTimeArg;
        $endTime = is_int($endTimeArg) ? $endTimeArg : (int) $endTimeArg;

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
        $request = $this->createPerformanceRequest($account, $startTime, $endTime, $module);

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

        // 确保响应是数组类型并包含必要的结构
        if (!is_array($response)
            || !isset($response['data'])
            || !is_array($response['data'])
            || !isset($response['data']['body'])
            || !is_array($response['data']['body'])
            || !isset($response['data']['body']['tables'])
            || !is_array($response['data']['body']['tables'])) {
            return;
        }

        foreach ($response['data']['body']['tables'] as $table) {
            if (is_array($table)) {
                /** @var array<string, mixed> $table */
                $this->processPerformanceTable($table, $account, $module);
            }
        }
    }

    private function createPerformanceRequest(Account $account, int $startTime, int $endTime, PerformanceModule $module): WechatPerformanceRequest
    {
        $request = new WechatPerformanceRequest();
        $request->setAccount($account);
        $request->setStartTimestamp((string) $startTime);
        $request->setEndTimestamp((string) $endTime);
        $request->setModule($module->value);

        $params = [
            'networktype' => '-1',
            'device_level' => '-1',
            'device' => '-1',
        ];
        $request->setParams($params);

        return $request;
    }

    /**
     * @param array<string, mixed> $table
     */
    private function processPerformanceTable(array $table, Account $account, PerformanceModule $module): void
    {
        $performance = $this->findOrCreatePerformance($table, $account, $module);

        if (!isset($table['lines']) || !is_array($table['lines'])) {
            return;
        }

        foreach ($table['lines'] as $line) {
            if (is_array($line) && isset($line['fields']) && is_array($line['fields'])) {
                /** @var array<int, array<string, mixed>> $fields */
                $fields = $line['fields'];
                $this->processPerformanceFields($fields, $performance);
            }
        }
    }

    /**
     * @param array<string, mixed> $table
     */
    private function findOrCreatePerformance(array $table, Account $account, PerformanceModule $module): Performance
    {
        // 确保必要的字段存在且是字符串类型
        $id = isset($table['id']) && is_string($table['id']) ? $table['id'] : '';
        $zh = isset($table['zh']) && is_string($table['zh']) ? $table['zh'] : '';

        $performance = $this->performanceRepository->findOneBy([
            'account' => $account,
            'name' => $id,
            'nameZh' => $zh,
            'module' => $module,
        ]);
        // Type is guaranteed by repository generic type and null check below

        if (null === $performance) {
            $performance = new Performance();
            $performance->setAccount($account);
            $performance->setName($id);
            $performance->setNameZh($zh);
            $performance->setModule($module);
            $this->entityManager->persist($performance);
            $this->entityManager->flush();
        }

        return $performance;
    }

    /**
     * @param array<int, array<string, mixed>> $fields
     */
    private function processPerformanceFields(array $fields, Performance $performance): void
    {
        foreach ($fields as $field) {
            if (!is_array($field)) {
                continue;
            }

            $refdate = isset($field['refdate']) && is_string($field['refdate']) ? $field['refdate'] : '';
            $value = isset($field['value']) && is_string($field['value']) ? $field['value'] : '';

            if ('' === $refdate) {
                continue;
            }

            $attribute = $this->attributeRepository->findOneBy([
                'name' => $refdate,
                'performance' => $performance,
            ]);
            // Type is guaranteed by repository generic type and null check below

            if (null === $attribute) {
                $attribute = new PerformanceAttribute();
                $attribute->setName($refdate);
                $attribute->setPerformance($performance);
            }

            $attribute->setValue($value);
            $this->entityManager->persist($attribute);
            $this->entityManager->flush();
        }
    }
}

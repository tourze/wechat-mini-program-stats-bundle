<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command\DataCube;

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
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetDailyRetainRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-retain/getDailyRetain.html
 */
#[AsCronTask(expression: '0 8 * * *')]
#[AsCronTask(expression: '0 9 * * *')]
#[AsCronTask(expression: '0 10 * * *')]
#[AsCronTask(expression: '0 12 * * *')]
#[AsCronTask(expression: '0 15 * * *')]
#[AsCronTask(expression: '0 20 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问小程序日留存')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
class GetDailyRetainCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:get-daily-retain';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyRetainDataRepository $logRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->processAccount($account);
        }

        return Command::SUCCESS;
    }

    private function processAccount(Account $account): void
    {
        $request = new GetDailyRetainRequest();
        $request->setAccount($account);
        $request->setBeginDate(CarbonImmutable::now()->subDays());
        $request->setEndDate(CarbonImmutable::now()->subDays());

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->warning('获取用户访问小程序日留存时发生异常', [
                'account' => $account,
                'exception' => $exception,
            ]);

            return;
        }

        if (!is_array($res)) {
            return;
        }

        /** @var array<string, mixed> $res */
        $this->processRetainData($res, $account, 'visit_uv_new');
        $this->processRetainData($res, $account, 'visit_uv');
    }

    /**
     * @param array<string, mixed> $res
     */
    private function processRetainData(array $res, Account $account, string $type): void
    {
        if (!isset($res[$type]) || !is_array($res[$type])) {
            return;
        }

        if (!isset($res['ref_date']) || !is_string($res['ref_date'])) {
            return;
        }

        foreach ($res[$type] as $value) {
            if (!is_array($value) || !isset($value['value'])) {
                continue;
            }

            $userNumber = is_string($value['value']) || is_null($value['value']) ? $value['value'] : null;

            $log = $this->findOrCreateLog($account, $res['ref_date'], $type);
            $log->setUserNumber($userNumber);
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }
    }

    private function findOrCreateLog(Account $account, string $refDate, string $type): DailyRetainData
    {
        /** @var DailyRetainData|null $log */
        $log = $this->logRepository->findOneBy([
            'date' => CarbonImmutable::parse($refDate),
            'account' => $account,
            'type' => $type,
        ]);

        if (null === $log) {
            $log = new DailyRetainData();
            $log->setAccount($account);
            $log->setDate(CarbonImmutable::parse($refDate));
            $log->setType($type);
        }

        return $log;
    }
}

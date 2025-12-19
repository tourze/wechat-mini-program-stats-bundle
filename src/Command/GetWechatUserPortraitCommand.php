<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command;

use Carbon\CarbonImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Service\WechatUserPortraitService;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getUserPortrait.html
 */
#[AsCronTask(expression: '1 12 * * *')]
#[AsCronTask(expression: '6 21 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户用户画像分布')]
#[Autoconfigure(public: true)]
final class GetWechatUserPortraitCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:user-portrait:get';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly WechatUserPortraitService $service,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->service->getDate($account, CarbonImmutable::now()->subDays(1), CarbonImmutable::now()->subDays(1));
            $this->service->getDate($account, CarbonImmutable::now()->subDays(7), CarbonImmutable::now()->subDays(1));
        }

        return Command::SUCCESS;
    }
}

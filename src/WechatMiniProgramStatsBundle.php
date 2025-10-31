<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineSnowflakeBundle\DoctrineSnowflakeBundle;
use Tourze\DoctrineTimestampBundle\DoctrineTimestampBundle;
use Tourze\DoctrineUpsertBundle\DoctrineUpsertBundle;
use Tourze\JsonRPCCacheBundle\JsonRPCCacheBundle;
use Tourze\JsonRPCLogBundle\JsonRPCLogBundle;
use Tourze\LockCommandBundle\LockCommandBundle;
use Tourze\Symfony\CronJob\CronJobBundle;
use WechatMiniProgramBundle\WechatMiniProgramBundle;

class WechatMiniProgramStatsBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineSnowflakeBundle::class => ['all' => true],
            DoctrineTimestampBundle::class => ['all' => true],
            DoctrineUpsertBundle::class => ['all' => true],
            JsonRPCCacheBundle::class => ['all' => true],
            JsonRPCLogBundle::class => ['all' => true],
            CronJobBundle::class => ['all' => true],
            LockCommandBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
        ];
    }
}

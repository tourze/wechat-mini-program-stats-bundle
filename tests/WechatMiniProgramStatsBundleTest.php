<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramStatsBundle\WechatMiniProgramStatsBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramStatsBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramStatsBundleTest extends AbstractBundleTestCase
{
}

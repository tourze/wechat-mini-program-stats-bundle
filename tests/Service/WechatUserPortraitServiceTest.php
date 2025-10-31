<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramStatsBundle\Service\WechatUserPortraitService;

/**
 * @internal
 */
#[CoversClass(WechatUserPortraitService::class)]
#[RunTestsInSeparateProcesses]
final class WechatUserPortraitServiceTest extends AbstractIntegrationTestCase
{
    private WechatUserPortraitService $service;

    protected function onSetUp(): void
    {
        $this->service = self::getService(WechatUserPortraitService::class);
    }

    public function testServiceCanBeInstantiated(): void
    {
        $this->assertInstanceOf(WechatUserPortraitService::class, $this->service);
    }

    public function testServiceExistsInContainer(): void
    {
        $container = self::getContainer();
        $this->assertTrue($container->has(WechatUserPortraitService::class));
    }
}

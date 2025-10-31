<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatMiniProgramStatsBundle\DependencyInjection\WechatMiniProgramStatsExtension;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramStatsExtension::class)]
final class WechatMiniProgramStatsExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testContainerHasServices(): void
    {
        $container = $this->getContainer();

        // 验证服务是否在容器中
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Command\CheckWcPerformanceCommand'));
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Command\GetPerformanceDataCommand'));
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Command\DataCube\GetDailySummaryCommand'));
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Repository\PerformanceRepository'));
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository'));
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Repository\UserPortraitDataRepository'));
        self::assertTrue($container->has('WechatMiniProgramStatsBundle\Service\WechatUserPortraitService'));
    }

    protected function getContainer(): ContainerInterface
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new WechatMiniProgramStatsExtension();
        $extension->load([], $container);

        return $container;
    }
}

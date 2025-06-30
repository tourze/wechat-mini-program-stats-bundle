<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramStatsBundle\DependencyInjection\WechatMiniProgramStatsExtension;
use WechatMiniProgramStatsBundle\WechatMiniProgramStatsBundle;

class WechatMiniProgramStatsBundleTest extends TestCase
{
    private WechatMiniProgramStatsBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new WechatMiniProgramStatsBundle();
    }

    public function testGetContainerExtension(): void
    {
        $extension = $this->bundle->getContainerExtension();
        
        $this->assertInstanceOf(WechatMiniProgramStatsExtension::class, $extension);
    }

    public function testBuild(): void
    {
        $container = new ContainerBuilder();
        
        $this->bundle->build($container);
        
        $this->assertInstanceOf(ContainerBuilder::class, $container);
    }
}
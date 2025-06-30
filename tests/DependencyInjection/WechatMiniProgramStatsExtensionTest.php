<?php

namespace WechatMiniProgramStatsBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\DependencyInjection\WechatMiniProgramStatsExtension;

class WechatMiniProgramStatsExtensionTest extends TestCase
{
    public function testExtensionExists(): void
    {
        $this->assertTrue(class_exists(WechatMiniProgramStatsExtension::class));
    }
}

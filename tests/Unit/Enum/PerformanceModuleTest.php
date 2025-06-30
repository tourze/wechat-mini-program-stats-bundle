<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;

class PerformanceModuleTest extends TestCase
{
    public function testEnumCases(): void
    {
        $cases = PerformanceModule::cases();
        
        $this->assertCount(5, $cases);
        $this->assertSame(PerformanceModule::TYPE_16, $cases[0]);
        $this->assertSame(PerformanceModule::TYPE_17, $cases[1]);
        $this->assertSame(PerformanceModule::TYPE_21, $cases[2]);
        $this->assertSame(PerformanceModule::TYPE_22, $cases[3]);
        $this->assertSame(PerformanceModule::TYPE_23, $cases[4]);
    }

    public function testEnumValues(): void
    {
        $this->assertSame('10016', PerformanceModule::TYPE_16->value);
        $this->assertSame('10017', PerformanceModule::TYPE_17->value);
        $this->assertSame('10021', PerformanceModule::TYPE_21->value);
        $this->assertSame('10022', PerformanceModule::TYPE_22->value);
        $this->assertSame('10023', PerformanceModule::TYPE_23->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('打开率', PerformanceModule::TYPE_16->getLabel());
        $this->assertSame('启动各阶段耗时', PerformanceModule::TYPE_17->getLabel());
        $this->assertSame('页面切换耗时', PerformanceModule::TYPE_21->getLabel());
        $this->assertSame('内存指标', PerformanceModule::TYPE_22->getLabel());
        $this->assertSame('内存异常', PerformanceModule::TYPE_23->getLabel());
    }

    public function testImplementsLabelable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Labelable::class, PerformanceModule::TYPE_16);
    }

    public function testImplementsItemable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Itemable::class, PerformanceModule::TYPE_16);
    }

    public function testImplementsSelectable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Selectable::class, PerformanceModule::TYPE_16);
    }
}
<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Enum\CostTimeType;

class CostTimeTypeTest extends TestCase
{
    public function testEnumCases(): void
    {
        $cases = CostTimeType::cases();
        
        $this->assertCount(3, $cases);
        $this->assertSame(CostTimeType::Launch, $cases[0]);
        $this->assertSame(CostTimeType::Download, $cases[1]);
        $this->assertSame(CostTimeType::Render, $cases[2]);
    }

    public function testEnumValues(): void
    {
        $this->assertSame(1, CostTimeType::Launch->value);
        $this->assertSame(2, CostTimeType::Download->value);
        $this->assertSame(3, CostTimeType::Render->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('启动总耗时', CostTimeType::Launch->getLabel());
        $this->assertSame('下载耗时', CostTimeType::Download->getLabel());
        $this->assertSame('初次渲染耗时', CostTimeType::Render->getLabel());
    }

    public function testImplementsLabelable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Labelable::class, CostTimeType::Launch);
    }

    public function testImplementsItemable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Itemable::class, CostTimeType::Launch);
    }

    public function testImplementsSelectable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Selectable::class, CostTimeType::Launch);
    }
}
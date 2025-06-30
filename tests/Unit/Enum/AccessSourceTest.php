<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Enum\AccessSource;

class AccessSourceTest extends TestCase
{
    public function testEnumCases(): void
    {
        $cases = AccessSource::cases();
        
        $this->assertCount(36, $cases);
        $this->assertSame(AccessSource::TYPE_1, $cases[0]);
        $this->assertSame(AccessSource::TYPE_36, $cases[35]);
    }

    public function testEnumValues(): void
    {
        $this->assertSame(1, AccessSource::TYPE_1->value);
        $this->assertSame(2, AccessSource::TYPE_2->value);
        $this->assertSame(36, AccessSource::TYPE_36->value);
    }

    public function testGetLabelForFirstFewCases(): void
    {
        $this->assertSame('小程序历史列表', AccessSource::TYPE_1->getLabel());
        $this->assertSame('搜索', AccessSource::TYPE_2->getLabel());
        $this->assertSame('会话', AccessSource::TYPE_3->getLabel());
        $this->assertSame('扫一扫二维码', AccessSource::TYPE_4->getLabel());
        $this->assertSame('公众号主页', AccessSource::TYPE_5->getLabel());
    }

    public function testGetLabelForLastFewCases(): void
    {
        $this->assertSame('微信广告', AccessSource::TYPE_33->getLabel());
        $this->assertSame('其他移动应用', AccessSource::TYPE_34->getLabel());
        $this->assertSame('发现入口-我的小程序', AccessSource::TYPE_35->getLabel());
        $this->assertSame('任务栏-我的小程序', AccessSource::TYPE_36->getLabel());
    }

    public function testImplementsLabelable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Labelable::class, AccessSource::TYPE_1);
    }

    public function testImplementsItemable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Itemable::class, AccessSource::TYPE_1);
    }

    public function testImplementsSelectable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Selectable::class, AccessSource::TYPE_1);
    }
}
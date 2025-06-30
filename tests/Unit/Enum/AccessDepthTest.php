<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Enum\AccessDepth;

class AccessDepthTest extends TestCase
{
    public function testEnumCases(): void
    {
        $cases = AccessDepth::cases();
        
        $this->assertCount(7, $cases);
        $this->assertSame(AccessDepth::TYPE_1, $cases[0]);
        $this->assertSame(AccessDepth::TYPE_2, $cases[1]);
        $this->assertSame(AccessDepth::TYPE_3, $cases[2]);
        $this->assertSame(AccessDepth::TYPE_4, $cases[3]);
        $this->assertSame(AccessDepth::TYPE_5, $cases[4]);
        $this->assertSame(AccessDepth::TYPE_6, $cases[5]);
        $this->assertSame(AccessDepth::TYPE_7, $cases[6]);
    }

    public function testEnumValues(): void
    {
        $this->assertSame(1, AccessDepth::TYPE_1->value);
        $this->assertSame(2, AccessDepth::TYPE_2->value);
        $this->assertSame(3, AccessDepth::TYPE_3->value);
        $this->assertSame(4, AccessDepth::TYPE_4->value);
        $this->assertSame(5, AccessDepth::TYPE_5->value);
        $this->assertSame(6, AccessDepth::TYPE_6->value);
        $this->assertSame(7, AccessDepth::TYPE_7->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('1 页', AccessDepth::TYPE_1->getLabel());
        $this->assertSame('2 页', AccessDepth::TYPE_2->getLabel());
        $this->assertSame('3 页', AccessDepth::TYPE_3->getLabel());
        $this->assertSame('4 页', AccessDepth::TYPE_4->getLabel());
        $this->assertSame('5 页', AccessDepth::TYPE_5->getLabel());
        $this->assertSame('6-10 页', AccessDepth::TYPE_6->getLabel());
        $this->assertSame('>10 页', AccessDepth::TYPE_7->getLabel());
    }

    public function testImplementsLabelable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Labelable::class, AccessDepth::TYPE_1);
    }

    public function testImplementsItemable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Itemable::class, AccessDepth::TYPE_1);
    }

    public function testImplementsSelectable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Selectable::class, AccessDepth::TYPE_1);
    }
}
<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Enum\AccessStayTime;

class AccessStayTimeTest extends TestCase
{
    public function testEnumCases(): void
    {
        $cases = AccessStayTime::cases();
        
        $this->assertCount(8, $cases);
        $this->assertSame(AccessStayTime::TYPE_1, $cases[0]);
        $this->assertSame(AccessStayTime::TYPE_2, $cases[1]);
        $this->assertSame(AccessStayTime::TYPE_3, $cases[2]);
        $this->assertSame(AccessStayTime::TYPE_4, $cases[3]);
        $this->assertSame(AccessStayTime::TYPE_5, $cases[4]);
        $this->assertSame(AccessStayTime::TYPE_6, $cases[5]);
        $this->assertSame(AccessStayTime::TYPE_7, $cases[6]);
        $this->assertSame(AccessStayTime::TYPE_8, $cases[7]);
    }

    public function testEnumValues(): void
    {
        $this->assertSame(1, AccessStayTime::TYPE_1->value);
        $this->assertSame(2, AccessStayTime::TYPE_2->value);
        $this->assertSame(3, AccessStayTime::TYPE_3->value);
        $this->assertSame(4, AccessStayTime::TYPE_4->value);
        $this->assertSame(5, AccessStayTime::TYPE_5->value);
        $this->assertSame(6, AccessStayTime::TYPE_6->value);
        $this->assertSame(7, AccessStayTime::TYPE_7->value);
        $this->assertSame(8, AccessStayTime::TYPE_8->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('0-2s', AccessStayTime::TYPE_1->getLabel());
        $this->assertSame('3-5s', AccessStayTime::TYPE_2->getLabel());
        $this->assertSame('6-10s', AccessStayTime::TYPE_3->getLabel());
        $this->assertSame('11-20s', AccessStayTime::TYPE_4->getLabel());
        $this->assertSame('20-30s', AccessStayTime::TYPE_5->getLabel());
        $this->assertSame('30-50s', AccessStayTime::TYPE_6->getLabel());
        $this->assertSame('50-100s', AccessStayTime::TYPE_7->getLabel());
        $this->assertSame('>100s', AccessStayTime::TYPE_8->getLabel());
    }

    public function testImplementsLabelable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Labelable::class, AccessStayTime::TYPE_1);
    }

    public function testImplementsItemable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Itemable::class, AccessStayTime::TYPE_1);
    }

    public function testImplementsSelectable(): void
    {
        $this->assertInstanceOf(\Tourze\EnumExtra\Selectable::class, AccessStayTime::TYPE_1);
    }
}
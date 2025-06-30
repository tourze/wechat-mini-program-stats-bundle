<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;

class WeeklyVisitTrendTest extends TestCase
{
    private WeeklyVisitTrend $weeklyVisitTrend;

    protected function setUp(): void
    {
        $this->weeklyVisitTrend = new WeeklyVisitTrend();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertEquals(0, $this->weeklyVisitTrend->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->weeklyVisitTrend->__toString();
        $this->assertSame('0', $result); // ID is 0 initially
    }
}

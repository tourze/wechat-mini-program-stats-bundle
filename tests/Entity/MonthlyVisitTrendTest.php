<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;

class MonthlyVisitTrendTest extends TestCase
{
    private MonthlyVisitTrend $monthlyVisitTrend;

    protected function setUp(): void
    {
        $this->monthlyVisitTrend = new MonthlyVisitTrend();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertEquals(0, $this->monthlyVisitTrend->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->monthlyVisitTrend->__toString();
        $this->assertSame('0', $result); // ID is 0 initially
    }
}

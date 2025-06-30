<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;

class DailyVisitTrendDataTest extends TestCase
{
    private DailyVisitTrendData $dailyVisitTrendData;

    protected function setUp(): void
    {
        $this->dailyVisitTrendData = new DailyVisitTrendData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->dailyVisitTrendData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->dailyVisitTrendData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

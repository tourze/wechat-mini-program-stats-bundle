<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;

class DailySummaryDataTest extends TestCase
{
    private DailySummaryData $dailySummaryData;

    protected function setUp(): void
    {
        $this->dailySummaryData = new DailySummaryData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->dailySummaryData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->dailySummaryData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

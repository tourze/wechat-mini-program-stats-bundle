<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;

class DailyRetainDataTest extends TestCase
{
    private DailyRetainData $dailyRetainData;

    protected function setUp(): void
    {
        $this->dailyRetainData = new DailyRetainData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->dailyRetainData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->dailyRetainData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

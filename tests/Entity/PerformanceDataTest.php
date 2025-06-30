<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\PerformanceData;

class PerformanceDataTest extends TestCase
{
    private PerformanceData $performanceData;

    protected function setUp(): void
    {
        $this->performanceData = new PerformanceData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->performanceData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->performanceData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

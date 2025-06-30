<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;

class HourVisitDataTest extends TestCase
{
    private HourVisitData $hourVisitData;

    protected function setUp(): void
    {
        $this->hourVisitData = new HourVisitData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->hourVisitData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->hourVisitData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

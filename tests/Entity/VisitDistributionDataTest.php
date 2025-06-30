<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\VisitDistributionData;

class VisitDistributionDataTest extends TestCase
{
    private VisitDistributionData $visitDistributionData;

    protected function setUp(): void
    {
        $this->visitDistributionData = new VisitDistributionData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->visitDistributionData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->visitDistributionData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

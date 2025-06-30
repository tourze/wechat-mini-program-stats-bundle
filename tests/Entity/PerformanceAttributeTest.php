<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;

class PerformanceAttributeTest extends TestCase
{
    private PerformanceAttribute $performanceAttribute;

    protected function setUp(): void
    {
        $this->performanceAttribute = new PerformanceAttribute();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertEquals(0, $this->performanceAttribute->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->performanceAttribute->__toString();
        $this->assertSame('0', $result); // ID is 0 initially
    }
}

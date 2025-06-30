<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\Performance;

class PerformanceTest extends TestCase
{
    private Performance $performance;

    protected function setUp(): void
    {
        $this->performance = new Performance();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertEquals(0, $this->performance->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->performance->__toString();
        $this->assertSame('0', $result); // ID is 0 initially
    }
}

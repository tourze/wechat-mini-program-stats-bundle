<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;

class OperationPerformanceTest extends TestCase
{
    private OperationPerformance $operationPerformance;

    protected function setUp(): void
    {
        $this->operationPerformance = new OperationPerformance();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertEquals(0, $this->operationPerformance->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->operationPerformance->__toString();
        $this->assertSame('0', $result); // ID is 0 initially
    }
}

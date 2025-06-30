<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\OperationPerformanceRepository;

class OperationPerformanceRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(OperationPerformanceRepository::class));
    }
}

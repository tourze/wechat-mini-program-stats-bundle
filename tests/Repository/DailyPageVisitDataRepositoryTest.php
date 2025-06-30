<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

class DailyPageVisitDataRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(DailyPageVisitDataRepository::class));
    }
}

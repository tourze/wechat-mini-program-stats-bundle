<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

class DailyNewUserVisitPvRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(DailyNewUserVisitPvRepository::class));
    }
}

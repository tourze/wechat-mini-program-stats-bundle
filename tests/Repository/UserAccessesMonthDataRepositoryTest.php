<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\UserAccessesMonthDataRepository;

class UserAccessesMonthDataRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(UserAccessesMonthDataRepository::class));
    }
}

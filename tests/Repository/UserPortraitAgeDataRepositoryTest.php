<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\UserPortraitAgeDataRepository;

class UserPortraitAgeDataRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(UserPortraitAgeDataRepository::class));
    }
}

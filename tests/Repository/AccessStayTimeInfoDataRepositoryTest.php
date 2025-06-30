<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\AccessStayTimeInfoDataRepository;

class AccessStayTimeInfoDataRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(AccessStayTimeInfoDataRepository::class));
    }
}

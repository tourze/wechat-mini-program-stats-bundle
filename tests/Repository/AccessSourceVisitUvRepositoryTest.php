<?php

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Repository\AccessSourceVisitUvRepository;

class AccessSourceVisitUvRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(AccessSourceVisitUvRepository::class));
    }
}

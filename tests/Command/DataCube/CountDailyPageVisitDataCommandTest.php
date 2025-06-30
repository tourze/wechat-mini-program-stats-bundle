<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\CountDailyPageVisitDataCommand;

class CountDailyPageVisitDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(CountDailyPageVisitDataCommand::class));
    }
}

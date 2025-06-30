<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\CountDailyNewUserVisitDataCommand;

class CountDailyNewUserVisitDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(CountDailyNewUserVisitDataCommand::class));
    }
}

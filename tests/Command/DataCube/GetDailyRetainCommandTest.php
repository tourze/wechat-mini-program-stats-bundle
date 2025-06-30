<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\GetDailyRetainCommand;

class GetDailyRetainCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetDailyRetainCommand::class));
    }
}

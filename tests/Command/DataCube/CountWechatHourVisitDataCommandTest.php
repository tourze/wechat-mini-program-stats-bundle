<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\CountWechatHourVisitDataCommand;

class CountWechatHourVisitDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(CountWechatHourVisitDataCommand::class));
    }
}

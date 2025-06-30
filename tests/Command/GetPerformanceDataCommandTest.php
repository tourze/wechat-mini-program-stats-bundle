<?php

namespace WechatMiniProgramStatsBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\GetPerformanceDataCommand;

class GetPerformanceDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetPerformanceDataCommand::class));
    }
}

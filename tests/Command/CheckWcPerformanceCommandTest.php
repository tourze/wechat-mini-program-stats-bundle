<?php

namespace WechatMiniProgramStatsBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\CheckWcPerformanceCommand;

class CheckWcPerformanceCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(CheckWcPerformanceCommand::class));
    }
}

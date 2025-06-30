<?php

namespace WechatMiniProgramStatsBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\GetUserAccessesMonthDataCommand;

class GetUserAccessesMonthDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetUserAccessesMonthDataCommand::class));
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\GetUserAccessesWeekDataCommand;

class GetUserAccessesWeekDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetUserAccessesWeekDataCommand::class));
    }
}

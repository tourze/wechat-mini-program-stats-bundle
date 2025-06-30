<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\GetWeeklyVisitTrendCommand;

class GetWeeklyVisitTrendCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetWeeklyVisitTrendCommand::class));
    }
}

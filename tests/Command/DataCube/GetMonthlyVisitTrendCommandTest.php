<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\GetMonthlyVisitTrendCommand;

class GetMonthlyVisitTrendCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetMonthlyVisitTrendCommand::class));
    }
}

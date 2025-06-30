<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramDailyVisitTrendData;

class GetWechatMiniProgramDailyVisitTrendDataTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramDailyVisitTrendData::class));
    }
}

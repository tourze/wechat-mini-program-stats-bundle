<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramDailyVisitTrendDataByDateRange;

class GetWechatMiniProgramDailyVisitTrendDataByDateRangeTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramDailyVisitTrendDataByDateRange::class));
    }
}

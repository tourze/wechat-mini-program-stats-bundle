<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange;

class GetWechatMiniProgramDailyVisitTrendDataTotalByDateRangeTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange::class));
    }
}

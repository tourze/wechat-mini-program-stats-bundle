<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramPageVisitTotalDataByDateRange;

class GetWechatMiniProgramPageVisitTotalDataByDateRangeTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramPageVisitTotalDataByDateRange::class));
    }
}

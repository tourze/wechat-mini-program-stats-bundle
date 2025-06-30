<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramVisitUvAverage;

class GetWechatMiniProgramVisitUvAverageTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramVisitUvAverage::class));
    }
}

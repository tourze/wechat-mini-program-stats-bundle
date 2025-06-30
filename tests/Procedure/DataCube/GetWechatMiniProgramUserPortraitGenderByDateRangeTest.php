<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitGenderByDateRange;

class GetWechatMiniProgramUserPortraitGenderByDateRangeTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramUserPortraitGenderByDateRange::class));
    }
}

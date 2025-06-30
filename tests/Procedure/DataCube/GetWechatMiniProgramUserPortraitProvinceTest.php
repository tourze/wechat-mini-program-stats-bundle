<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitProvince;

class GetWechatMiniProgramUserPortraitProvinceTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramUserPortraitProvince::class));
    }
}

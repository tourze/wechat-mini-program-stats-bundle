<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitAge;

class GetWechatMiniProgramUserPortraitAgeTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramUserPortraitAge::class));
    }
}

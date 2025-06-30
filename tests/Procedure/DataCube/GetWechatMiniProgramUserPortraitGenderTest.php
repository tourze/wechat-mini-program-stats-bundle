<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitGender;

class GetWechatMiniProgramUserPortraitGenderTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramUserPortraitGender::class));
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramRetainUserByDate;

class GetWechatMiniProgramRetainUserByDateTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramRetainUserByDate::class));
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramVisitTotalUser;

class GetWechatMiniProgramVisitTotalUserTest extends TestCase
{
    public function testProcedureExists(): void
    {
        $this->assertTrue(class_exists(GetWechatMiniProgramVisitTotalUser::class));
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\GetWechatUserAccessPageDataCommand;

class GetWechatUserAccessPageDataCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(GetWechatUserAccessPageDataCommand::class));
    }
}

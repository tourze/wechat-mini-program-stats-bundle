<?php

namespace WechatMiniProgramStatsBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Command\SyncGetOperationPerformanceCommand;

class SyncGetOperationPerformanceCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $this->assertTrue(class_exists(SyncGetOperationPerformanceCommand::class));
    }
}

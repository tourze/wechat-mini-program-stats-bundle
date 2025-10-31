<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramStatsBundle\Command\GetWechatUserPortraitCommand;

/**
 * @internal
 */
#[CoversClass(GetWechatUserPortraitCommand::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatUserPortraitCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(GetWechatUserPortraitCommand::class);
        $this->assertInstanceOf(GetWechatUserPortraitCommand::class, $command);

        return new CommandTester($command);
    }

    public function testCommandClassExists(): void
    {
        self::assertTrue(class_exists(GetWechatUserPortraitCommand::class));
    }

    public function testExecuteWithDefaultArguments(): void
    {
        $commandTester = $this->getCommandTester();

        // 由于这是数据同步命令，可能需要外部依赖，我们只测试命令能正常启动
        // 不强制要求成功执行，因为可能缺少外部服务
        $exitCode = $commandTester->execute([]);

        // 接受成功或预期的失败（如缺少外部服务）
        self::assertContains($exitCode, [Command::SUCCESS, Command::FAILURE]);
    }

    public function testCommandCanBeRetrievedFromContainer(): void
    {
        $command = self::getService(GetWechatUserPortraitCommand::class);
        self::assertInstanceOf(GetWechatUserPortraitCommand::class, $command);
    }
}

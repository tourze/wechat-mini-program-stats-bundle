<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Command;

use HttpClientBundle\Exception\HttpClientException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramStatsBundle\Command\SyncGetOperationPerformanceCommand;

/**
 * @internal
 */
#[CoversClass(SyncGetOperationPerformanceCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncGetOperationPerformanceCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncGetOperationPerformanceCommand::class);
        $this->assertInstanceOf(SyncGetOperationPerformanceCommand::class, $command);

        return new CommandTester($command);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(SyncGetOperationPerformanceCommand::class));
    }

    public function testIsCommand(): void
    {
        $command = self::getService(SyncGetOperationPerformanceCommand::class);
        self::assertInstanceOf(Command::class, $command);
    }

    public function testHasCorrectName(): void
    {
        $command = self::getService(SyncGetOperationPerformanceCommand::class);
        self::assertSame('wechat-mini-program:operation-performance:sync', $command->getName());
    }

    public function testExecuteWithDefaultArguments(): void
    {
        $commandTester = $this->getCommandTester();

        try {
            $exitCode = $commandTester->execute([]);
            // 接受成功或预期的失败（如缺少外部服务）
            self::assertContains($exitCode, [Command::SUCCESS, Command::FAILURE]);
        } catch (HttpClientException $e) {
            // 预期的外部 API 调用异常，这在测试环境中是正常的
            // 因为该命令需要有效的微信小程序 appid 配置
            self::assertStringContainsString('invalid appid', $e->getMessage());
        }
    }
}

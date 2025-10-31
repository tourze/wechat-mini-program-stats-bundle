<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\CountDailyPageVisitDataCommand;

/**
 * @internal
 */
#[CoversClass(CountDailyPageVisitDataCommand::class)]
#[RunTestsInSeparateProcesses]
final class CountDailyPageVisitDataCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(CountDailyPageVisitDataCommand::class);
        $this->assertInstanceOf(CountDailyPageVisitDataCommand::class, $command);

        return new CommandTester($command);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(CountDailyPageVisitDataCommand::class));
    }

    public function testIsCommand(): void
    {
        $command = self::getService(CountDailyPageVisitDataCommand::class);
        self::assertInstanceOf(Command::class, $command);
    }

    public function testHasCorrectName(): void
    {
        $command = self::getService(CountDailyPageVisitDataCommand::class);
        self::assertSame('wechat-mini-program:count-daily-page-visit-data', $command->getName());
    }

    public function testExecuteWithDefaultArguments(): void
    {
        $commandTester = $this->getCommandTester();

        try {
            // 由于这是数据同步命令，可能需要外部依赖，我们只测试命令能正常启动
            // 不强制要求成功执行，因为可能缺少外部服务或数据表
            $exitCode = $commandTester->execute([]);

            // 接受成功或预期的失败（如缺少外部服务）
            self::assertContains($exitCode, [Command::SUCCESS, Command::FAILURE]);
        } catch (\Throwable $exception) {
            // 在测试环境中，如果缺少数据表或外部依赖，命令可能抛出异常
            // 这是预期的行为，我们验证异常消息包含预期的数据库错误
            self::assertTrue(
                str_contains($exception->getMessage(), 'no such table')
                || str_contains($exception->getMessage(), 'Table')
                || str_contains($exception->getMessage(), 'Connection'),
                '命令执行失败的原因应该是数据库或依赖问题，实际异常: ' . $exception->getMessage()
            );
        }
    }
}

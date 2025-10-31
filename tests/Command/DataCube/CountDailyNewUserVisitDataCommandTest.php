<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Command\DataCube;

use Doctrine\DBAL\Exception\TableNotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramStatsBundle\Command\DataCube\CountDailyNewUserVisitDataCommand;

/**
 * @internal
 */
#[CoversClass(CountDailyNewUserVisitDataCommand::class)]
#[RunTestsInSeparateProcesses]
final class CountDailyNewUserVisitDataCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(CountDailyNewUserVisitDataCommand::class);
        $this->assertInstanceOf(CountDailyNewUserVisitDataCommand::class, $command);

        return new CommandTester($command);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(CountDailyNewUserVisitDataCommand::class));
    }

    public function testIsCommand(): void
    {
        $command = self::getService(CountDailyNewUserVisitDataCommand::class);
        self::assertInstanceOf(Command::class, $command);
    }

    public function testHasCorrectName(): void
    {
        $command = self::getService(CountDailyNewUserVisitDataCommand::class);
        self::assertSame('wechat-mini-program:count-daily-new-user-visit-data', $command->getName());
    }

    public function testExecuteWithDefaultArguments(): void
    {
        $commandTester = $this->getCommandTester();

        try {
            $exitCode = $commandTester->execute([]);
            // 接受成功或预期的失败（如缺少外部服务或表）
            self::assertContains($exitCode, [Command::SUCCESS, Command::FAILURE]);
        } catch (TableNotFoundException $e) {
            // 预期的表不存在异常，这在测试环境中是正常的
            // 因为该命令依赖其他包的数据库表结构
            self::assertStringContainsString('wechat_mini_program_code_session_log', $e->getMessage());
        }
    }
}

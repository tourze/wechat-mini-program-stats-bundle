<?php

namespace WechatMiniProgramStatsBundle\Tests\Command;

use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Command\GetWechatUserPortraitCommand;
use WechatMiniProgramStatsBundle\Service\WechatUserPortraitService;

class GetWechatUserPortraitCommandTest extends TestCase
{
    private GetWechatUserPortraitCommand $command;
    private MockObject|AccountRepository $accountRepository;
    private MockObject|WechatUserPortraitService $service;
    private MockObject|InputInterface $input;
    private MockObject|OutputInterface $output;
    private ReflectionMethod $executeMethod;

    protected function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->service = $this->createMock(WechatUserPortraitService::class);

        $this->command = new GetWechatUserPortraitCommand(
            $this->accountRepository,
            $this->service
        );

        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);

        // 使用反射访问protected方法
        $this->executeMethod = new ReflectionMethod(GetWechatUserPortraitCommand::class, 'execute');
        $this->executeMethod->setAccessible(true);
    }

    public function testExecute_withValidAccounts_callsServiceForEachAccount(): void
    {
        // 创建测试数据
        $account1 = $this->createMock(Account::class);
        $account2 = $this->createMock(Account::class);

        // 模拟AccountRepository返回有效账号
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account1, $account2]);

        // 修复Carbon::now()的测试问题，使用Carbon::setTestNow()
        Carbon::setTestNow(Carbon::parse('2023-01-10'));

        // 在 PHPUnit 10 中，at() 和 withConsecutive() 都已被移除
        // 我们可以简单地验证 getDate 被调用了预期的次数
        $this->service->expects($this->exactly(4))
            ->method('getDate');

        // 执行命令并验证返回值
        $result = $this->executeMethod->invoke($this->command, $this->input, $this->output);

        $this->assertEquals(Command::SUCCESS, $result);

        // 清除测试时间
        Carbon::setTestNow();
    }

    public function testExecute_withNoValidAccounts_returnsSuccessWithoutCallingService(): void
    {
        // 模拟AccountRepository返回空数组
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);

        // 验证service方法不被调用
        $this->service->expects($this->never())
            ->method('getDate');

        // 使用反射方法执行命令
        $result = $this->executeMethod->invoke($this->command, $this->input, $this->output);

        $this->assertEquals(Command::SUCCESS, $result);
    }
}

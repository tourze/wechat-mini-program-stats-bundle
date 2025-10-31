<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramVisitUvAverage;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramVisitUvAverage::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramVisitUvAverageTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramVisitUvAverage::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramVisitUvAverage::class);
        $procedure->accountId = 'invalid-account';
        $procedure->day = '7';

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute();
    }

    public function testExecuteWithValidData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('valid-account');
        $account->setAppSecret('test-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramVisitUvAverage::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = '7';

        $result = $procedure->execute();

        self::assertArrayHasKey('average', $result);
        self::assertArrayHasKey('compare', $result);
    }

    public function testExecuteWithNoData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('valid-account');
        $account->setAppSecret('test-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramVisitUvAverage::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = '7';

        $result = $procedure->execute();

        self::assertArrayHasKey('average', $result);
        self::assertArrayHasKey('compare', $result);
        self::assertEquals(0, $result['average']);
    }
}

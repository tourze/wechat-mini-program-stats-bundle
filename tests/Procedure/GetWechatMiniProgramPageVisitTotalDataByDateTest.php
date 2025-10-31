<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramPageVisitTotalDataByDate;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramPageVisitTotalDataByDate::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramPageVisitTotalDataByDateTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramPageVisitTotalDataByDate::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramPageVisitTotalDataByDate::class);
        $procedure->accountId = 'invalid-account';

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

        $procedure = self::getService(GetWechatMiniProgramPageVisitTotalDataByDate::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->date = '2024-01-01';

        $result = $procedure->execute();

        $expected = [
            'total' => null,
            'totalCompare' => null,
            'totalSevenCompare' => null,
        ];

        self::assertEquals($expected, $result);
    }
}

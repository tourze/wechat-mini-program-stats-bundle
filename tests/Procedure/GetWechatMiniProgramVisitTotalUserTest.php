<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramVisitTotalUser;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramVisitTotalUser::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramVisitTotalUserTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramVisitTotalUser::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramVisitTotalUser::class);
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

        $summaryData = new DailySummaryData();
        $summaryData->setAccount($account);
        $summaryData->setDate(new \DateTimeImmutable('2023-01-01'));
        $summaryData->setVisitTotal('1000');
        self::getEntityManager()->persist($summaryData);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramVisitTotalUser::class);
        $procedure->accountId = (string) $account->getId();

        $result = $procedure->execute();

        $expected = [
            'total' => '1000',
            'totalCompare' => 0,
            'totalSevenCompare' => 0,
        ];

        self::assertEquals($expected, $result);
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

        $procedure = self::getService(GetWechatMiniProgramVisitTotalUser::class);
        $procedure->accountId = (string) $account->getId();

        $result = $procedure->execute();

        $expected = [
            'total' => 0,
            'totalCompare' => 0,
            'totalSevenCompare' => 0,
        ];

        self::assertEquals($expected, $result);
    }
}

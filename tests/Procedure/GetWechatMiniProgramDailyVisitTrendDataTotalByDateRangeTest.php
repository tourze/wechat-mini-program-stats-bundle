<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramDailyVisitTrendDataTotalByDateRangeParam;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramDailyVisitTrendDataTotalByDateRangeTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange::class);
        $param = new GetWechatMiniProgramDailyVisitTrendDataTotalByDateRangeParam(
            accountId: 'invalid-account',
            startDate: '2023-01-01',
            endDate: '2023-01-07'
        );

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute($param);
    }

    public function testExecuteWithValidData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);

        $data1 = new DailyVisitTrendData();
        $data1->setAccount($account);
        $data1->setDate(new \DateTimeImmutable('2023-01-01'));
        $data1->setSessionCnt(100);
        $data1->setVisitPv(200);
        $data1->setVisitUv(80);
        $data1->setVisitUvNew(20);
        self::getEntityManager()->persist($data1);

        $data2 = new DailyVisitTrendData();
        $data2->setAccount($account);
        $data2->setDate(new \DateTimeImmutable('2023-01-02'));
        $data2->setSessionCnt(120);
        $data2->setVisitPv(220);
        $data2->setVisitUv(90);
        $data2->setVisitUvNew(25);
        self::getEntityManager()->persist($data2);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange::class);
        $param = new GetWechatMiniProgramDailyVisitTrendDataTotalByDateRangeParam(
            accountId: (string) $account->getId(),
            startDate: '2023-01-01',
            endDate: '2023-01-07'
        );

        $result = $procedure->execute($param);
        $resultArray = $result->toArray();

        self::assertArrayHasKey('sessionCntTotal', $resultArray);
        self::assertArrayHasKey('visitPvTotal', $resultArray);
        self::assertArrayHasKey('visitUvTotal', $resultArray);
        self::assertArrayHasKey('visitUvNewTotal', $resultArray);
    }

    public function testExecuteWithEmptyData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange::class);
        $param = new GetWechatMiniProgramDailyVisitTrendDataTotalByDateRangeParam(
            accountId: (string) $account->getId(),
            startDate: '2023-01-01',
            endDate: '2023-01-07'
        );

        $result = $procedure->execute($param);
        $resultArray = $result->toArray();

        self::assertArrayHasKey('sessionCntTotal', $resultArray);
        self::assertArrayHasKey('visitPvTotal', $resultArray);
        self::assertArrayHasKey('visitUvTotal', $resultArray);
        self::assertArrayHasKey('visitUvNewTotal', $resultArray);
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramDailyVisitTrendDataParam;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramDailyVisitTrendData;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramDailyVisitTrendData::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramDailyVisitTrendDataTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramDailyVisitTrendData::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendData::class);
        $param = new GetWechatMiniProgramDailyVisitTrendDataParam(
            accountId: 'invalid-account',
            date: '2023-01-01'
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

        $currentData = new DailyVisitTrendData();
        $currentData->setAccount($account);
        $currentData->setDate(new \DateTimeImmutable('2023-01-10'));
        $currentData->setSessionCnt(100);
        $currentData->setVisitPv(200);
        $currentData->setVisitUv(80);
        $currentData->setVisitUvNew(20);
        self::getEntityManager()->persist($currentData);

        $previousData = new DailyVisitTrendData();
        $previousData->setAccount($account);
        $previousData->setDate(new \DateTimeImmutable('2023-01-09'));
        $previousData->setSessionCnt(90);
        $previousData->setVisitPv(180);
        $previousData->setVisitUv(70);
        $previousData->setVisitUvNew(15);
        self::getEntityManager()->persist($previousData);

        $sevenDaysAgoData = new DailyVisitTrendData();
        $sevenDaysAgoData->setAccount($account);
        $sevenDaysAgoData->setDate(new \DateTimeImmutable('2023-01-03'));
        $sevenDaysAgoData->setSessionCnt(80);
        $sevenDaysAgoData->setVisitPv(160);
        $sevenDaysAgoData->setVisitUv(60);
        $sevenDaysAgoData->setVisitUvNew(10);
        self::getEntityManager()->persist($sevenDaysAgoData);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendData::class);
        $param = new GetWechatMiniProgramDailyVisitTrendDataParam(
            accountId: (string) $account->getId(),
            date: '2023-01-10'
        );

        $result = $procedure->execute($param);
        $resultArray = $result->toArray();

        self::assertArrayHasKey('sessionCnt', $resultArray);
        self::assertArrayHasKey('visitPv', $resultArray);
        self::assertArrayHasKey('visitUv', $resultArray);
        self::assertArrayHasKey('visitUvNew', $resultArray);
        self::assertEquals(100, $resultArray['sessionCnt']);
        self::assertEquals(200, $resultArray['visitPv']);
        self::assertEquals(80, $resultArray['visitUv']);
        self::assertEquals(20, $resultArray['visitUvNew']);
    }

    public function testExecuteWithNoCurrentData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendData::class);
        $param = new GetWechatMiniProgramDailyVisitTrendDataParam(
            accountId: (string) $account->getId(),
            date: '2023-01-10'
        );

        $result = $procedure->execute($param);
        $resultArray = $result->toArray();

        self::assertArrayHasKey('sessionCnt', $resultArray);
        self::assertEquals(0, $resultArray['sessionCnt']);
    }
}

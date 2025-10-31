<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
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
        $procedure->accountId = 'invalid-account';
        $procedure->date = '2023-01-01';

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute();
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
        $procedure->accountId = (string) $account->getId();
        $procedure->date = '2023-01-10';

        $result = $procedure->execute();

        self::assertArrayHasKey('sessionCnt', $result);
        self::assertArrayHasKey('visitPv', $result);
        self::assertArrayHasKey('visitUv', $result);
        self::assertArrayHasKey('visitUvNew', $result);
        self::assertEquals(100, $result['sessionCnt']);
        self::assertEquals(200, $result['visitPv']);
        self::assertEquals(80, $result['visitUv']);
        self::assertEquals(20, $result['visitUvNew']);
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
        $procedure->accountId = (string) $account->getId();
        $procedure->date = '2023-01-10';

        $result = $procedure->execute();

        self::assertArrayHasKey('sessionCnt', $result);
        self::assertEquals(0, $result['sessionCnt']);
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramNewUserVisitByDateParam;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramNewUserVisitByDate;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramNewUserVisitByDate::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramNewUserVisitByDateTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramNewUserVisitByDate::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramNewUserVisitByDate::class);
        $param = new GetWechatMiniProgramNewUserVisitByDateParam(
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

        $currentVisitData = new DailyNewUserVisitPv();
        $currentVisitData->setAccount($account);
        $currentVisitData->setDate(new \DateTimeImmutable('2023-01-01'));
        $currentVisitData->setVisitPv(100);
        self::getEntityManager()->persist($currentVisitData);

        $beforeVisitData = new DailyNewUserVisitPv();
        $beforeVisitData->setAccount($account);
        $beforeVisitData->setDate(new \DateTimeImmutable('2022-12-31'));
        $beforeVisitData->setVisitPv(80);
        self::getEntityManager()->persist($beforeVisitData);

        $beforeSevenVisitData = new DailyNewUserVisitPv();
        $beforeSevenVisitData->setAccount($account);
        $beforeSevenVisitData->setDate(new \DateTimeImmutable('2022-12-25'));
        $beforeSevenVisitData->setVisitPv(60);
        self::getEntityManager()->persist($beforeSevenVisitData);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramNewUserVisitByDate::class);
        $param = new GetWechatMiniProgramNewUserVisitByDateParam(
            accountId: (string) $account->getId(),
            date: '2023-01-01'
        );

        $result = $procedure->execute($param);
        $resultArray = $result->toArray();

        self::assertArrayHasKey('visitPv', $resultArray);
        self::assertEquals(100, $resultArray['visitPv']);
    }

    public function testExecuteWithNoData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramNewUserVisitByDate::class);
        $param = new GetWechatMiniProgramNewUserVisitByDateParam(
            accountId: (string) $account->getId(),
            date: '2023-01-01'
        );

        $result = $procedure->execute($param);

        $expected = [
            'visitPv' => 0,
            'visitPvCompare' => null,
            'visitPvSevenCompare' => null,
        ];
        self::assertEquals($expected, $result->toArray());
    }
}

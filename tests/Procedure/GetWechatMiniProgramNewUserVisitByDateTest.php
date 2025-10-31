<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
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
        $procedure->accountId = (string) $account->getId();
        $procedure->date = '2023-01-01';

        $result = $procedure->execute();

        self::assertArrayHasKey('visitPv', $result);
        self::assertEquals(100, $result['visitPv']);
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
        $procedure->accountId = (string) $account->getId();
        $procedure->date = '2023-01-01';

        $result = $procedure->execute();

        $expected = [
            'visitPv' => 0,
            'visitPvCompare' => null,
            'visitPvSevenCompare' => null,
        ];
        self::assertEquals($expected, $result);
    }
}

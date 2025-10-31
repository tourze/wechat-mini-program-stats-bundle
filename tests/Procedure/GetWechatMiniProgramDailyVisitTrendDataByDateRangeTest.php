<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramDailyVisitTrendDataByDateRange;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramDailyVisitTrendDataByDateRange::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramDailyVisitTrendDataByDateRangeTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramDailyVisitTrendDataByDateRange::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendDataByDateRange::class);
        $procedure->accountId = 'invalid-account';
        $procedure->startDate = '2023-01-01';
        $procedure->endDate = '2023-01-07';

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute();
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

        $procedure = self::getService(GetWechatMiniProgramDailyVisitTrendDataByDateRange::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->startDate = '2023-01-01';
        $procedure->endDate = '2023-01-07';

        $result = $procedure->execute();

        self::assertEquals(['data' => []], $result);
    }
}

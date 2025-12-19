<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramPageVisitTotalDataByDateRangeParam;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramPageVisitTotalDataByDateRange;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramPageVisitTotalDataByDateRange::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramPageVisitTotalDataByDateRangeTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramPageVisitTotalDataByDateRange::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramPageVisitTotalDataByDateRange::class);
        $param = new GetWechatMiniProgramPageVisitTotalDataByDateRangeParam(
            accountId: 'invalid-account',
            startDate: '2023-01-01',
            endDate: '2023-01-07'
        );

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute($param);
    }
}

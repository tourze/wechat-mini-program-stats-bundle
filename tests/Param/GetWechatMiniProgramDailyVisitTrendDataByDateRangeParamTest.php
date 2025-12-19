<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam::class)]
final class GetWechatMiniProgramDailyVisitTrendDataByDateRangeParamTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $param = new GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam();
        self::assertSame('', $param->accountId);
        self::assertSame('', $param->startDate);
        self::assertSame('', $param->endDate);
    }

    public function testConstructorWithValues(): void
    {
        $param = new GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam(
            'test-account',
            '20250101',
            '20250131'
        );

        self::assertSame('test-account', $param->accountId);
        self::assertSame('20250101', $param->startDate);
        self::assertSame('20250131', $param->endDate);
    }

    public function testImplementsRpcParamInterface(): void
    {
        $param = new GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam();
        self::assertInstanceOf(RpcParamInterface::class, $param);
    }
}
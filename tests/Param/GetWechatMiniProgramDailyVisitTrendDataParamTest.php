<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramDailyVisitTrendDataParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramDailyVisitTrendDataParam::class)]
final class GetWechatMiniProgramDailyVisitTrendDataParamTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $param = new GetWechatMiniProgramDailyVisitTrendDataParam();
        self::assertSame('', $param->accountId);
        self::assertSame('', $param->date);
    }

    public function testConstructorWithValues(): void
    {
        $param = new GetWechatMiniProgramDailyVisitTrendDataParam(
            'test-account',
            '20250115'
        );

        self::assertSame('test-account', $param->accountId);
        self::assertSame('20250115', $param->date);
    }

    public function testImplementsRpcParamInterface(): void
    {
        $param = new GetWechatMiniProgramDailyVisitTrendDataParam();
        self::assertInstanceOf(RpcParamInterface::class, $param);
    }
}
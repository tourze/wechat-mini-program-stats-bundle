<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramVisitUvAverageParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramVisitUvAverageParam::class)]
final class GetWechatMiniProgramVisitUvAverageParamTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $param = new GetWechatMiniProgramVisitUvAverageParam();
        self::assertSame('', $param->accountId);
        self::assertSame('', $param->day);
    }

    public function testConstructorWithValues(): void
    {
        $param = new GetWechatMiniProgramVisitUvAverageParam(
            'test-account',
            '7'
        );

        self::assertSame('test-account', $param->accountId);
        self::assertSame('7', $param->day);
    }

    public function testImplementsRpcParamInterface(): void
    {
        $param = new GetWechatMiniProgramVisitUvAverageParam();
        self::assertInstanceOf(RpcParamInterface::class, $param);
    }
}
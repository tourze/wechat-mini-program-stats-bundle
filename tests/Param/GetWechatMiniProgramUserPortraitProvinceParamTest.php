<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramUserPortraitProvinceParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramUserPortraitProvinceParam::class)]
final class GetWechatMiniProgramUserPortraitProvinceParamTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $param = new GetWechatMiniProgramUserPortraitProvinceParam();
        self::assertSame('', $param->accountId);
        self::assertSame(1, $param->day);
    }

    public function testConstructorWithValues(): void
    {
        $param = new GetWechatMiniProgramUserPortraitProvinceParam(
            'test-account',
            7
        );

        self::assertSame('test-account', $param->accountId);
        self::assertSame(7, $param->day);
    }

    public function testImplementsRpcParamInterface(): void
    {
        $param = new GetWechatMiniProgramUserPortraitProvinceParam();
        self::assertInstanceOf(RpcParamInterface::class, $param);
    }
}
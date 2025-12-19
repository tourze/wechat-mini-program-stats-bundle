<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramVisitTotalUserParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramVisitTotalUserParam::class)]
final class GetWechatMiniProgramVisitTotalUserParamTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $param = new GetWechatMiniProgramVisitTotalUserParam();
        self::assertSame('', $param->accountId);
    }

    public function testConstructorWithValues(): void
    {
        $param = new GetWechatMiniProgramVisitTotalUserParam(
            'test-account'
        );

        self::assertSame('test-account', $param->accountId);
    }

    public function testImplementsRpcParamInterface(): void
    {
        $param = new GetWechatMiniProgramVisitTotalUserParam();
        self::assertInstanceOf(RpcParamInterface::class, $param);
    }
}
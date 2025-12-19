<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Param;

use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;

readonly class GetWechatMiniProgramUserPortraitGenderParam implements RpcParamInterface
{
    public function __construct(
        #[MethodParam(description: '小程序ID')]
        public string $accountId = '',
        #[MethodParam(description: '天，时间范围支持昨天1、最近7天、最近30天')]
        public int $day = 1,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Param;

use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;

readonly class GetWechatMiniProgramNewUserVisitByDateParam implements RpcParamInterface
{
    public function __construct(
        #[MethodParam(description: '小程序ID')]
        public string $accountId = '',
        #[MethodParam(description: '日期')]
        public string $date = '',
    ) {
    }
}

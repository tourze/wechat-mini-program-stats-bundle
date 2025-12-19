<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Param;

use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;

readonly class GetWechatMiniProgramPageVisitTotalDataByDateRangeParam implements RpcParamInterface
{
    public function __construct(
        #[MethodParam(description: '小程序ID')]
        public string $accountId = '',
        #[MethodParam(description: '开始日期')]
        public string $startDate = '',
        #[MethodParam(description: '结束日期')]
        public string $endDate = '',
    ) {
    }
}

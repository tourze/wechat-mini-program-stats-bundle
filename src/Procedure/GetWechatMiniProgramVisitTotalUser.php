<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Repository\DailySummaryDataRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序累计用户数')]
#[MethodExpose('GetWechatMiniProgramVisitTotalUser')]
class GetWechatMiniProgramVisitTotalUser extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailySummaryDataRepository $dailySummaryDataRepository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->dailySummaryDataRepository->findBy([
            'account' => $account,
        ], ['date' => 'DESC'], 8);

        return [
            'total' => $row[0]->getVisitTotal(),
            'totalCompare' => round(($row[0]->getVisitTotal() - $row[1]->getVisitTotal()) / $row[1]->getVisitTotal(), 4),
            'totalSevenCompare' => round(($row[0]->getVisitTotal() - $row[7]->getVisitTotal()) / $row[7]->getVisitTotal(), 4),
        ];
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramVisitTotalUser_{$request->getParams()->get('accountId')}";
    }

    protected function getCacheDuration(JsonRpcRequest $request): int
    {
        return 60 * 60;
    }

    protected function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield null;
    }
}

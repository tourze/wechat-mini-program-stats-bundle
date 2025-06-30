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
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序累计用户数')]
#[MethodExpose(method: 'GetWechatMiniProgramVisitTotalUser')]
class GetWechatMiniProgramVisitTotalUser extends CacheableProcedure
{
    #[MethodParam(description: '小程序ID')]
    public string $accountId = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailySummaryDataRepository $dailySummaryDataRepository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if ($account === null) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->dailySummaryDataRepository->findBy([
            'account' => $account,
        ], ['date' => 'DESC'], 8);

        return [
            'total' => $row[0]->getVisitTotal(),
            'totalCompare' => ($row[1]->getVisitTotal() !== null && $row[1]->getVisitTotal() > 0) ? round(((int) $row[0]->getVisitTotal() - (int) $row[1]->getVisitTotal()) / (int) $row[1]->getVisitTotal(), 4) : 0,
            'totalSevenCompare' => ($row[7]->getVisitTotal() !== null && $row[7]->getVisitTotal() > 0) ? round(((int) $row[0]->getVisitTotal() - (int) $row[7]->getVisitTotal()) / (int) $row[7]->getVisitTotal(), 4) : 0,
        ];
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramVisitTotalUser_{$request->getParams()->get('accountId')}";
    }

    public function getCacheDuration(JsonRpcRequest $request): int
    {
        return 60 * 60;
    }

    public function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield null;
    }
}

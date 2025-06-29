<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\CarbonImmutable;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序日期间隔内的页面访问总数')]
#[MethodExpose('GetWechatMiniProgramPageVisitTotalDataByDateRange')]
class GetWechatMiniProgramPageVisitTotalDataByDateRange extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('开始日期')]
    public string $startDate = '';

    #[MethodParam('结束日期')]
    public string $endDate = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserAccessPageDataRepository $pageDataRepository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if ($account === null) {
            throw new ApiException('找不到小程序');
        }

        $date = CarbonImmutable::parse($this->endDate)->startOfDay();
        $list = [];
        while ($date->gt(CarbonImmutable::parse($this->startDate))) {
            $row = $this->pageDataRepository->createQueryBuilder('p')
                ->select('sum(p.pageVisitPv)')
                ->where('p.account = :account and p.date = :date')
                ->setParameter('account', $account)
                ->setParameter('date', CarbonImmutable::parse($date)->startOfDay())
                ->getQuery()
                ->getSingleScalarResult();
            if ($row < 1) {
                continue;
            }
            $list[] = [
                'date' => $date,
                'total' => $row,
            ];
            $date = $date->subDay();
        }

        return $list;
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramPageVisitTotalDataByDateRange_{$request->getParams()->get('accountId')}_" .
            CarbonImmutable::parse($request->getParams()->get('startDate'))->startOfDay() . '_' . CarbonImmutable::parse($request->getParams()->get('endDate'))->startOfDay();
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

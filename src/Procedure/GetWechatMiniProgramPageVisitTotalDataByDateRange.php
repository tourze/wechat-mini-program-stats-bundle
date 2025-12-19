<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\CarbonImmutable;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPC\Core\Result\ArrayResult;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramPageVisitTotalDataByDateRangeParam;
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序日期间隔内的页面访问总数')]
#[MethodExpose(method: 'GetWechatMiniProgramPageVisitTotalDataByDateRange')]
class GetWechatMiniProgramPageVisitTotalDataByDateRange extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserAccessPageDataRepository $pageDataRepository,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramPageVisitTotalDataByDateRangeParam $param
     */
    public function execute(GetWechatMiniProgramPageVisitTotalDataByDateRangeParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $date = CarbonImmutable::parse($param->endDate)->startOfDay();
        $list = [];
        while ($date->gt(CarbonImmutable::parse($param->startDate))) {
            $row = $this->pageDataRepository->createQueryBuilder('p')
                ->select('sum(p.pageVisitPv)')
                ->where('p.account = :account and p.date = :date')
                ->setParameter('account', $account)
                ->setParameter('date', CarbonImmutable::parse($date)->startOfDay())
                ->getQuery()
                ->getSingleScalarResult()
            ;
            if ($row < 1) {
                continue;
            }
            $list[] = [
                'date' => $date,
                'total' => $row,
            ];
            $date = $date->subDay();
        }

        return new ArrayResult(['data' => $list]);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramPageVisitTotalDataByDateRange_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $startDate = $params->get('startDate');
        $endDate = $params->get('endDate');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($startDate) || !is_string($endDate)) {
            return "GetWechatMiniProgramPageVisitTotalDataByDateRange_{$accountId}_invalid_dates";
        }

        return "GetWechatMiniProgramPageVisitTotalDataByDateRange_{$accountId}_" .
            CarbonImmutable::parse($startDate)->startOfDay() . '_' . CarbonImmutable::parse($endDate)->startOfDay();
    }

    public function getCacheDuration(JsonRpcRequest $request): int
    {
        return 60 * 60;
    }

    /**
     * @return iterable<string>
     */
    public function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield 'wechat_stats';
    }
}

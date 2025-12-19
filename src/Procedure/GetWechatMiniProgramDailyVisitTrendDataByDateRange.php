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
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序指定时间段内的日趋势数据')]
#[MethodExpose(method: 'GetWechatMiniProgramDailyVisitTrendDataByDateRange')]
class GetWechatMiniProgramDailyVisitTrendDataByDateRange extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $trendDataRepository,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam $param
     */
    public function execute(GetWechatMiniProgramDailyVisitTrendDataByDateRangeParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->trendDataRepository->createQueryBuilder('t')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', CarbonImmutable::parse($param->startDate)->startOfDay())
            ->setParameter('end', CarbonImmutable::parse($param->endDate)->startOfDay())
            ->getQuery()
            ->getResult()
        ;

        $list = [];
        if (is_iterable($row)) {
            foreach ($row as $item) {
                if (is_object($item) && method_exists($item, 'retrieveApiArray')) {
                    $list[] = $item->retrieveApiArray();
                }
            }
        }

        return new ArrayResult(['data' => $list]);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramDailyVisitTrendDataByDateRange_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $startDate = $params->get('startDate');
        $endDate = $params->get('endDate');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($startDate) || !is_string($endDate)) {
            return "GetWechatMiniProgramDailyVisitTrendDataByDateRange_{$accountId}_invalid_dates";
        }

        return "GetWechatMiniProgramDailyVisitTrendDataByDateRange_{$accountId}_"
            . CarbonImmutable::parse($startDate)->startOfDay()
            . '_' . CarbonImmutable::parse($endDate)->startOfDay();
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
        yield 'wechat_daily_visit_trend';
    }
}

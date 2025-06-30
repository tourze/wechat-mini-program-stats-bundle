<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\CarbonImmutable;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序指定时间段内的日趋势累计数据')]
#[MethodExpose(method: 'GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange')]
#[WithMonologChannel(channel: 'procedure')]
class GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange extends CacheableProcedure
{
    #[MethodParam(description: '小程序ID')]
    public string $accountId = '';

    #[MethodParam(description: '开始日期')]
    public string $startDate = '';

    #[MethodParam(description: '结束日期')]
    public string $endDate = '';

    public function __construct(
        private readonly GetWechatMiniProgramDailyVisitTrendDataByDateRange $logic,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function execute(): array
    {
        $this->logic->accountId = $this->accountId;
        $this->logic->startDate = $this->startDate;
        $this->logic->endDate = $this->endDate;
        $list = $this->logic->execute();

        $sessionCntArr = array_column($list, 'sessionCnt');
        $visitPvArr = array_column($list, 'visitPv');
        $visitUvArr = array_column($list, 'visitUv');
        $visitUvNewArr = array_column($list, 'visitUvNew');

        $res = [
            'sessionCntTotal' => array_sum($sessionCntArr),
            'visitPvTotal' => array_sum($visitPvArr),
            'visitUvTotal' => array_sum($visitUvArr),
            'visitUvNewTotal' => array_sum($visitUvNewArr),
        ];

        $day = CarbonImmutable::parse($this->startDate)->diffInDays(CarbonImmutable::parse($this->endDate));
        $this->logic->startDate = CarbonImmutable::parse($this->startDate)->subDays($day);
        $this->logic->endDate = CarbonImmutable::parse($this->endDate)->subDays($day);
        $beforeList = $this->logic->execute();

        $beforeSessionCntTotal = array_sum(array_column($beforeList, 'sessionCnt'));
        $beforeVisitPvTotal = array_sum(array_column($beforeList, 'visitPv'));
        $beforeVisitUvTotal = array_sum(array_column($beforeList, 'visitUv'));
        $beforeVisitUvNewTotal = array_sum(array_column($beforeList, 'visitUvNew'));

        $res['sessionCntCompare'] = $beforeSessionCntTotal > 0 ? round(($res['sessionCntTotal'] - $beforeSessionCntTotal) / $beforeSessionCntTotal, 4) : null;
        $res['visitPvCompare'] = $beforeVisitPvTotal > 0 ? round(($res['visitPvTotal'] - $beforeVisitPvTotal) / $beforeVisitPvTotal, 4) : null;
        $res['visitUvCompare'] = $beforeVisitUvTotal > 0 ? round(($res['visitUvTotal'] - $beforeVisitUvTotal) / $beforeVisitUvTotal, 4) : null;
        $res['visitUvNewCompare'] = $beforeVisitUvNewTotal > 0 ? round(($res['visitUvNewTotal'] - $beforeVisitUvNewTotal) / $beforeVisitUvNewTotal, 4) : null;

        $this->logger->info('所有数据', [
            'res' => $res,
            'beforeSessionCntTotal' => $beforeSessionCntTotal,
            'beforeVisitPvTotal' => $beforeVisitPvTotal,
            'beforeVisitUvTotal' => $beforeVisitUvTotal,
            'beforeVisitUvNewTotal' => $beforeVisitUvNewTotal,
        ]);

        return $res;
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange_{$request->getParams()->get('accountId')}_" .
            CarbonImmutable::parse($request->getParams()->get('startDate'))->startOfDay()
            . '_' . CarbonImmutable::parse($request->getParams()->get('endDate'))->startOfDay();
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

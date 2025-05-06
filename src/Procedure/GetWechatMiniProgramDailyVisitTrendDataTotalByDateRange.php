<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序指定时间段内的日趋势累计数据')]
#[MethodExpose('GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange')]
class GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('开始日期')]
    public string $startDate = '';

    #[MethodParam('结束日期')]
    public string $endDate = '';

    public function __construct(
        private readonly GetWechatMiniProgramDailyVisitTrendDataByDateRange $logic,
        private readonly LoggerInterface $procedureLogger,
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

        $day = Carbon::parse($this->startDate)->diffInDays(Carbon::parse($this->endDate));
        $this->logic->startDate = Carbon::parse($this->startDate)->subDays($day);
        $this->logic->endDate = Carbon::parse($this->endDate)->subDays($day);
        $beforeList = $this->logic->execute();

        $beforeSessionCntTotal = array_sum(array_column($beforeList, 'sessionCnt'));
        $beforeVisitPvTotal = array_sum(array_column($beforeList, 'visitPv'));
        $beforeVisitUvTotal = array_sum(array_column($beforeList, 'visitUv'));
        $beforeVisitUvNewTotal = array_sum(array_column($beforeList, 'visitUvNew'));

        $res['sessionCntCompare'] = $beforeSessionCntTotal > 0 ? round(($res['sessionCntTotal'] - $beforeSessionCntTotal) / $beforeSessionCntTotal, 4) : null;
        $res['visitPvCompare'] = $beforeVisitPvTotal > 0 ? round(($res['visitPvTotal'] - $beforeVisitPvTotal) / $beforeVisitPvTotal, 4) : null;
        $res['visitUvCompare'] = $beforeVisitUvTotal > 0 ? round(($res['visitUvTotal'] - $beforeVisitUvTotal) / $beforeVisitUvTotal, 4) : null;
        $res['visitUvNewCompare'] = $beforeVisitUvNewTotal > 0 ? round(($res['visitUvNewTotal'] - $beforeVisitUvNewTotal) / $beforeVisitUvNewTotal, 4) : null;

        $this->procedureLogger->info('所有数据', [
            'res' => $res,
            'beforeSessionCntTotal' => $beforeSessionCntTotal,
            'beforeVisitPvTotal' => $beforeVisitPvTotal,
            'beforeVisitUvTotal' => $beforeVisitUvTotal,
            'beforeVisitUvNewTotal' => $beforeVisitUvNewTotal,
        ]);

        return $res;
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange_{$request->getParams()->get('accountId')}_" .
            Carbon::parse($request->getParams()->get('startDate'))->startOfDay()
            . '_' . Carbon::parse($request->getParams()->get('endDate'))->startOfDay();
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

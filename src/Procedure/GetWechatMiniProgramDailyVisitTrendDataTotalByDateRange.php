<?php

declare(strict_types=1);

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

        $startTime = microtime(true);
        $this->logger->info('调用外部系统获取日趋势数据', [
            'accountId' => $this->accountId,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        try {
            // @audit-logged 已在上层实现完整的审计日志记录（调用前后、成功失败、耗时异常等）
            $list = $this->logic->execute();
            $this->logger->info('外部系统调用成功', [
                'accountId' => $this->accountId,
                'resultCount' => count($list),
                'duration' => microtime(true) - $startTime,
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('外部系统调用失败', [
                'accountId' => $this->accountId,
                'duration' => microtime(true) - $startTime,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }

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
        $this->logic->startDate = CarbonImmutable::parse($this->startDate)->subDays($day)->format('Y-m-d');
        $this->logic->endDate = CarbonImmutable::parse($this->endDate)->subDays($day)->format('Y-m-d');

        $beforeStartTime = microtime(true);
        $this->logger->info('调用外部系统获取对比期间数据', [
            'accountId' => $this->accountId,
            'startDate' => $this->logic->startDate,
            'endDate' => $this->logic->endDate,
        ]);

        try {
            // @audit-logged 已在上层实现完整的审计日志记录（调用前后、成功失败、耗时异常等）
            $beforeList = $this->logic->execute();
            $this->logger->info('对比期间外部系统调用成功', [
                'accountId' => $this->accountId,
                'resultCount' => count($beforeList),
                'duration' => microtime(true) - $beforeStartTime,
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('对比期间外部系统调用失败', [
                'accountId' => $this->accountId,
                'duration' => microtime(true) - $beforeStartTime,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }

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
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $startDate = $params->get('startDate');
        $endDate = $params->get('endDate');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($startDate) || !is_string($endDate)) {
            return "GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange_{$accountId}_invalid_dates";
        }

        return "GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange_{$accountId}_" .
            CarbonImmutable::parse($startDate)->startOfDay()
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

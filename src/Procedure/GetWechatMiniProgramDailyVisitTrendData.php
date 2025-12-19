<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\CarbonImmutable;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
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
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramDailyVisitTrendDataParam;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序数据日趋势')]
#[MethodExpose(method: 'GetWechatMiniProgramDailyVisitTrendData')]
#[WithMonologChannel(channel: 'procedure')]
class GetWechatMiniProgramDailyVisitTrendData extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $trendDataRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramDailyVisitTrendDataParam $param
     */
    public function execute(GetWechatMiniProgramDailyVisitTrendDataParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        /** @var DailyVisitTrendData|null $row */
        $row = $this->trendDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($param->date)->startOfDay(),
        ]);
        if (null === $row) {
            $row = new DailyVisitTrendData();
        }

        /** @var DailyVisitTrendData|null $beforeRow */
        $beforeRow = $this->trendDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($param->date)->subDay()->startOfDay(),
        ]);

        /** @var DailyVisitTrendData|null $beforeSevenRow */
        $beforeSevenRow = $this->trendDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($param->date)->subDays(7)->startOfDay(),
        ]);

        $this->logger->info('所有数据', [
            'date' => CarbonImmutable::parse($param->date)->startOfDay(),
            'row' => $row,
            'beforeRow' => $beforeRow,
            'beforeSevenRow' => $beforeSevenRow,
        ]);

        return new ArrayResult([
            ...$row->retrieveApiArray(),
            ...$this->calculateDailyComparisons($row, $beforeRow),
            ...$this->calculateSevenDayComparisons($row, $beforeSevenRow),
        ]);
    }

    /**
     * @return array<string, float|null>
     */
    private function calculateDailyComparisons(DailyVisitTrendData $current, ?DailyVisitTrendData $previous): array
    {
        if (null === $previous) {
            return [
                'sessionCntCompare' => null,
                'visitPvCompare' => null,
                'visitUvCompare' => null,
                'visitUvNewCompare' => null,
            ];
        }

        return [
            'sessionCntCompare' => $this->calculatePercentageChange($current->getSessionCnt(), $previous->getSessionCnt()),
            'visitPvCompare' => $this->calculatePercentageChange($current->getVisitPv(), $previous->getVisitPv()),
            'visitUvCompare' => $this->calculatePercentageChange($current->getVisitUv(), $previous->getVisitUv()),
            'visitUvNewCompare' => $this->calculatePercentageChange($current->getVisitUvNew(), $previous->getVisitUvNew()),
        ];
    }

    /**
     * @return array<string, float|null>
     */
    private function calculateSevenDayComparisons(DailyVisitTrendData $current, ?DailyVisitTrendData $sevenDaysAgo): array
    {
        if (null === $sevenDaysAgo) {
            return [
                'sessionCntSevenCompare' => null,
                'visitPvSevenCompare' => null,
                'visitUvSevenCompare' => null,
                'visitUvNewSevenCompare' => null,
            ];
        }

        return [
            'sessionCntSevenCompare' => $this->calculatePercentageChange($current->getSessionCnt(), $sevenDaysAgo->getSessionCnt()),
            'visitPvSevenCompare' => $this->calculatePercentageChange($current->getVisitPv(), $sevenDaysAgo->getVisitPv()),
            'visitUvSevenCompare' => $this->calculatePercentageChange($current->getVisitUv(), $sevenDaysAgo->getVisitUv()),
            'visitUvNewSevenCompare' => $this->calculatePercentageChange($current->getVisitUvNew(), $sevenDaysAgo->getVisitUvNew()),
        ];
    }

    private function calculatePercentageChange(?int $current, ?int $previous): ?float
    {
        if (null === $current || null === $previous || 0 === $previous) {
            return null;
        }

        return round(($current - $previous) / $previous, 4);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramDailyVisitTrendData_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $date = $params->get('date');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($date)) {
            return "GetWechatMiniProgramDailyVisitTrendData_{$accountId}_invalid_date";
        }

        return "GetWechatMiniProgramDailyVisitTrendData_{$accountId}_"
            . CarbonImmutable::parse($date)->startOfDay();
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

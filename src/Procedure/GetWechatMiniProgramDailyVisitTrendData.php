<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\CarbonImmutable;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序数据日趋势')]
#[MethodExpose('GetWechatMiniProgramDailyVisitTrendData')]
#[WithMonologChannel('procedure')]
class GetWechatMiniProgramDailyVisitTrendData extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('日期')]
    public string $date = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $trendDataRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if ($account === null) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->trendDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($this->date)->startOfDay(),
        ]);
        if ($row === null) {
            $row = new DailyVisitTrendData();
        }

        $beforeRow = $this->trendDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($this->date)->subDay()->startOfDay(),
        ]);

        $beforeSevenRow = $this->trendDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($this->date)->subDays(7)->startOfDay(),
        ]);

        $this->logger->info('所有数据', [
            'date' => CarbonImmutable::parse($this->date)->startOfDay(),
            'row' => $row,
            'beforeRow' => $beforeRow,
            'beforeSevenRow' => $beforeSevenRow,
        ]);

        return [
            ...$row->retrieveApiArray(),
            'sessionCntCompare' => ($beforeRow !== null) ? round(($row->getSessionCnt() - $beforeRow->getSessionCnt()) / $beforeRow->getSessionCnt(), 4) : null,
            'visitPvCompare' => ($beforeRow !== null) ? round(($row->getVisitPv() - $beforeRow->getVisitPv()) / $beforeRow->getVisitPv(), 4) : null,
            'visitUvCompare' => ($beforeRow !== null) ? round(($row->getVisitUv() - $beforeRow->getVisitUv()) / $beforeRow->getVisitUv(), 4) : null,
            'visitUvNewCompare' => ($beforeRow !== null) ? round(($row->getVisitUvNew() - $beforeRow->getVisitUvNew()) / $beforeRow->getVisitUvNew(), 4) : null,
            'sessionCntSevenCompare' => ($beforeSevenRow !== null) ? round(($row->getSessionCnt() - $beforeSevenRow->getSessionCnt()) / $beforeSevenRow->getSessionCnt(), 4) : null,
            'visitPvSevenCompare' => ($beforeSevenRow !== null) ? round(($row->getVisitPv() - $beforeSevenRow->getVisitPv()) / $beforeSevenRow->getVisitPv(), 4) : null,
            'visitUvSevenCompare' => ($beforeSevenRow !== null) ? round(($row->getVisitUv() - $beforeSevenRow->getVisitUv()) / $beforeSevenRow->getVisitUv(), 4) : null,
            'visitUvNewSevenCompare' => ($beforeSevenRow !== null) ? round(($row->getVisitUvNew() - $beforeSevenRow->getVisitUvNew()) / $beforeSevenRow->getVisitUvNew(), 4) : null,
        ];
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramDailyVisitTrendData_{$request->getParams()->get('accountId')}_"
            . CarbonImmutable::parse($request->getParams()->get('date'))->startOfDay();
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

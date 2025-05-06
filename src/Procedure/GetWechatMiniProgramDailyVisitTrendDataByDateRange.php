<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\Carbon;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序指定时间段内的日趋势数据')]
#[MethodExpose('GetWechatMiniProgramDailyVisitTrendDataByDateRange')]
class GetWechatMiniProgramDailyVisitTrendDataByDateRange extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('开始日期')]
    public string $startDate = '';

    #[MethodParam('结束日期')]
    public string $endDate = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $trendDataRepository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->trendDataRepository->createQueryBuilder('t')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', Carbon::parse($this->startDate)->startOfDay())
            ->setParameter('end', Carbon::parse($this->endDate)->startOfDay())
            ->getQuery()
            ->getResult();

        $list = [];
        foreach ($row as $item) {
            $list[] = $item->retrieveApiArray();
        }

        return $list;
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramDailyVisitTrendDataByDateRange_{$request->getParams()->get('accountId')}_"
            . Carbon::parse($request->getParams()->get('startDate'))->startOfDay()
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

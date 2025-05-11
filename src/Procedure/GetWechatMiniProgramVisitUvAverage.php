<?php

namespace WechatMiniProgramStatsBundle\Procedure;

use Carbon\Carbon;
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
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

/**
 * 人均打开次数=总访问次数/总访问人数
 */
#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序人均打开次数')]
#[MethodExpose('GetWechatMiniProgramVisitUvAverage')]
#[WithMonologChannel('procedure')]
class GetWechatMiniProgramVisitUvAverage extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('天')]
    public string $day = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $trendDataRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $visitUv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitUv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', Carbon::now()->subDays($this->day))
            ->setParameter('end', Carbon::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();

        $visitPv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitPv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', Carbon::now()->subDays($this->day))
            ->setParameter('end', Carbon::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();
        $res = [
            'average' => intval($visitUv) < 1 ? 0 : intval($visitPv) / intval($visitUv),
        ];

        $beforeVisitUv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitUv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', Carbon::now()->subDays(intval($this->day) + intval($this->day)))
            ->setParameter('end', Carbon::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();

        $beforeVisitPv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitPv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', Carbon::now()->subDays(intval($this->day) + intval($this->day)))
            ->setParameter('end', Carbon::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();

        $this->logger->info('所有数据', [
            'visitUv' => intval($visitUv),
            'visitPv' => intval($visitPv),
            'beforeVisitUv' => intval($beforeVisitUv),
            'beforeVisitPv' => intval($beforeVisitPv),
        ]);

        $beforeAverage = intval($beforeVisitUv) < 1 ? 0 : intval($beforeVisitPv) / intval($beforeVisitUv);
        $res['compare'] = $beforeAverage > 0 ? round(($res['average'] - $beforeAverage) / $beforeAverage, 4) : null;

        return $res;
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramVisitUvAverage_{$request->getParams()->get('accountId')}_{$request->getParams()->get('day')}";
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

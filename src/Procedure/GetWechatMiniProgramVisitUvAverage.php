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
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramVisitUvAverageParam;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

/**
 * 人均打开次数=总访问次数/总访问人数
 */
#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序人均打开次数')]
#[MethodExpose(method: 'GetWechatMiniProgramVisitUvAverage')]
#[WithMonologChannel(channel: 'procedure')]
class GetWechatMiniProgramVisitUvAverage extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyVisitTrendDataRepository $trendDataRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramVisitUvAverageParam $param
     */
    public function execute(GetWechatMiniProgramVisitUvAverageParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $visitUv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitUv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', CarbonImmutable::now()->subDays((int) $param->day))
            ->setParameter('end', CarbonImmutable::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $visitPv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitPv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', CarbonImmutable::now()->subDays((int) $param->day))
            ->setParameter('end', CarbonImmutable::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;
        $res = [
            'average' => intval($visitUv) < 1 ? 0 : intval($visitPv) / intval($visitUv),
        ];

        $beforeVisitUv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitUv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', CarbonImmutable::now()->subDays(intval($param->day) + intval($param->day)))
            ->setParameter('end', CarbonImmutable::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $beforeVisitPv = $this->trendDataRepository->createQueryBuilder('t')
            ->select('sum(t.visitPv)')
            ->where('t.account = :account and t.date between :start and :end')
            ->setParameter('account', $account)
            ->setParameter('start', CarbonImmutable::now()->subDays(intval($param->day) + intval($param->day)))
            ->setParameter('end', CarbonImmutable::now()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $this->logger->info('所有数据', [
            'visitUv' => intval($visitUv),
            'visitPv' => intval($visitPv),
            'beforeVisitUv' => intval($beforeVisitUv),
            'beforeVisitPv' => intval($beforeVisitPv),
        ]);

        $beforeAverage = intval($beforeVisitUv) < 1 ? 0 : intval($beforeVisitPv) / intval($beforeVisitUv);
        $res['compare'] = $beforeAverage > 0 ? round(($res['average'] - $beforeAverage) / $beforeAverage, 4) : null;

        return new ArrayResult($res);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramVisitUvAverage_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $day = $params->get('day') ?? 'unknown';

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }
        if (!is_string($day) && !is_numeric($day)) {
            $day = 'unknown';
        }

        return "GetWechatMiniProgramVisitUvAverage_{$accountId}_{$day}";
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

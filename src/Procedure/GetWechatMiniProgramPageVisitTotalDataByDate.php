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
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramPageVisitTotalDataByDateParam;
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序指定日期的页面访问总数')]
#[MethodExpose(method: 'GetWechatMiniProgramPageVisitTotalDataByDate')]
class GetWechatMiniProgramPageVisitTotalDataByDate extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserAccessPageDataRepository $pageDataRepository,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramPageVisitTotalDataByDateParam $param
     */
    public function execute(GetWechatMiniProgramPageVisitTotalDataByDateParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->pageDataRepository->createQueryBuilder('p')
            ->select('sum(p.pageVisitPv)')
            ->where('p.account = :account and p.date = :date')
            ->setParameter('account', $account)
            ->setParameter('date', CarbonImmutable::parse($param->date)->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $beforeRow = $this->pageDataRepository->createQueryBuilder('p')
            ->select('sum(p.pageVisitPv)')
            ->where('p.account = :account and p.date = :date')
            ->setParameter('account', $account)
            ->setParameter('date', CarbonImmutable::parse($param->date)->subDay()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $beforeSevenRow = $this->pageDataRepository->createQueryBuilder('p')
            ->select('sum(p.pageVisitPv)')
            ->where('p.account = :account and p.date = :date')
            ->setParameter('account', $account)
            ->setParameter('date', CarbonImmutable::parse($param->date)->subDays(7)->startOfDay())
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return new ArrayResult([
            'total' => $row,
            'totalCompare' => (null !== $beforeRow && is_numeric($beforeRow) && $beforeRow > 0 && is_numeric($row)) ? round(((float) $row - (float) $beforeRow) / (float) $beforeRow, 4) : null,
            'totalSevenCompare' => (null !== $beforeSevenRow && is_numeric($beforeSevenRow) && $beforeSevenRow > 0 && is_numeric($row)) ? round(((float) $row - (float) $beforeSevenRow) / (float) $beforeSevenRow, 4) : null,
        ]);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramPageVisitTotalDataByDate_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $date = $params->get('date');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($date)) {
            return "GetWechatMiniProgramPageVisitTotalDataByDate_{$accountId}_invalid_date";
        }

        return "GetWechatMiniProgramPageVisitTotalDataByDate_{$accountId}_" . CarbonImmutable::parse($date)->startOfDay();
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
        yield 'wechat_page_visit';
    }
}

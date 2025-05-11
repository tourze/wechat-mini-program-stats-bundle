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
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序指定日期的页面访问总数')]
#[MethodExpose('GetWechatMiniProgramPageVisitTotalDataByDate')]
class GetWechatMiniProgramPageVisitTotalDataByDate extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('日期')]
    public string $date = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserAccessPageDataRepository $pageDataRepository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->pageDataRepository->createQueryBuilder('p')
            ->select('sum(p.pageVisitPv)')
            ->where('p.account = :account and p.date = :date')
            ->setParameter('account', $account)
            ->setParameter('date', Carbon::parse($this->date)->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();

        $beforeRow = $this->pageDataRepository->createQueryBuilder('p')
            ->select('sum(p.pageVisitPv)')
            ->where('p.account = :account and p.date = :date')
            ->setParameter('account', $account)
            ->setParameter('date', Carbon::parse($this->date)->subDay()->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();

        $beforeSevenRow = $this->pageDataRepository->createQueryBuilder('p')
            ->select('sum(p.pageVisitPv)')
            ->where('p.account = :account and p.date = :date')
            ->setParameter('account', $account)
            ->setParameter('date', Carbon::parse($this->date)->subDays(7)->startOfDay())
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total' => $row,
            'totalCompare' => $beforeRow ? round(($row - $beforeRow) / $beforeRow, 4) : null,
            'totalSevenCompare' => $beforeSevenRow ? round(($row - $beforeSevenRow) / $beforeSevenRow, 4) : null,
        ];
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramPageVisitTotalDataByDate_{$request->getParams()->get('accountId')}_" . Carbon::parse($request->getParams()->get('date'))->startOfDay();
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

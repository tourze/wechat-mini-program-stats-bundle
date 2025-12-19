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
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramNewUserVisitByDateParam;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取新用户访问小程序次数')]
#[MethodExpose(method: 'GetWechatMiniProgramNewUserVisitByDate')]
class GetWechatMiniProgramNewUserVisitByDate extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyNewUserVisitPvRepository $repository,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramNewUserVisitByDateParam $param
     */
    public function execute(GetWechatMiniProgramNewUserVisitByDateParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        /** @var DailyNewUserVisitPv|null $row */
        $row = $this->repository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($param->date)->startOfDay(),
        ]);
        if (null === $row) {
            $row = new DailyNewUserVisitPv();
        }

        /** @var DailyNewUserVisitPv|null $beforeRow */
        $beforeRow = $this->repository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($param->date)->subDay()->startOfDay(),
        ]);

        /** @var DailyNewUserVisitPv|null $beforeSevenRow */
        $beforeSevenRow = $this->repository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::parse($param->date)->subDays(7)->startOfDay(),
        ]);

        return new ArrayResult([
            'visitPv' => $row->getVisitPv(),
            'visitPvCompare' => (null !== $beforeRow && null !== $beforeRow->getVisitPv() && $beforeRow->getVisitPv() > 0) ? round(((int) $row->getVisitPv() - (int) $beforeRow->getVisitPv()) / (int) $beforeRow->getVisitPv(), 4) : null,
            'visitPvSevenCompare' => (null !== $beforeSevenRow && null !== $beforeSevenRow->getVisitPv() && $beforeSevenRow->getVisitPv() > 0) ? round(((int) $row->getVisitPv() - (int) $beforeSevenRow->getVisitPv()) / (int) $beforeSevenRow->getVisitPv(), 4) : null,
        ]);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramNewUserVisitByDate_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $date = $params->get('date');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($date)) {
            return "GetWechatMiniProgramNewUserVisitByDate_{$accountId}_invalid_date";
        }

        return "GetWechatMiniProgramNewUserVisitByDate_{$accountId}_" . CarbonImmutable::parse($date)->startOfDay();
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

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
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序留存用户')]
#[MethodExpose('GetWechatMiniProgramRetainUserByDate')]
class GetWechatMiniProgramRetainUserByDate extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('日期')]
    public string $date = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyRetainDataRepository $repository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $visitUv = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->startOfDay(),
            'type' => 'visit_uv',
        ]);

        $visitUvNew = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->startOfDay(),
            'type' => 'visit_uv_new',
        ]);

        $beforeVisitUv = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->subDay()->startOfDay(),
            'type' => 'visit_uv',
        ]);

        $beforeVisitUvNew = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->subDay()->startOfDay(),
            'type' => 'visit_uv_new',
        ]);

        $beforeSevenVisitUv = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->subDays(7)->startOfDay(),
            'type' => 'visit_uv',
        ]);

        $beforeSevenVisitUvNew = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->subDays(7)->startOfDay(),
            'type' => 'visit_uv_new',
        ]);

        $res = [
            'visitUv' => $visitUv ? $visitUv->getUserNumber() : 0,  // 活跃用户留存
            'visitUvNew' => $visitUvNew ? $visitUvNew->getUserNumber() : 0, // 新增用户留存
        ];

        return [
            ...$res,
            'visitUvCompare' => $beforeVisitUv ? round(($res['visitUv'] - $beforeVisitUv->getUserNumber()) / $beforeVisitUv->getUserNumber(), 4) : null,
            'visitUvNewCompare' => $beforeVisitUvNew ? round(($res['visitUvNew'] - $beforeVisitUvNew->getUserNumber()) / $beforeVisitUvNew->getUserNumber(), 4) : null,
            'visitUvSevenCompare' => $beforeSevenVisitUv ? round(($res['visitUv'] - $beforeSevenVisitUv->getUserNumber()) / $beforeSevenVisitUv->getUserNumber(), 4) : null,
            'visitUvNewSevenCompare' => $beforeSevenVisitUvNew ? round(($res['visitUvNew'] - $beforeSevenVisitUvNew->getUserNumber()) / $beforeSevenVisitUvNew->getUserNumber(), 4) : null,
        ];
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramRetainUserByDate_{$request->getParams()->get('accountId')}_" . Carbon::parse($request->getParams()->get('date'))->startOfDay();
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

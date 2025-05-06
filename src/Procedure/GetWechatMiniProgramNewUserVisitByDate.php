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
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取新用户访问小程序次数')]
#[MethodExpose('GetWechatMiniProgramNewUserVisitByDate')]
class GetWechatMiniProgramNewUserVisitByDate extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('日期')]
    public string $date = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyNewUserVisitPvRepository $repository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $row = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->startOfDay(),
        ]);
        if (!$row) {
            $row = new DailyNewUserVisitPv();
        }

        $beforeRow = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->subDay()->startOfDay(),
        ]);

        $beforeSevenRow = $this->repository->findOneBy([
            'account' => $account,
            'date' => Carbon::parse($this->date)->subDays(7)->startOfDay(),
        ]);

        return [
            'visitPv' => $row->getVisitPv(),
            'visitPvCompare' => $beforeRow && $beforeRow->getVisitPv() > 0 ? round(($row->getVisitPv() - $beforeRow->getVisitPv()) / $beforeRow->getVisitPv(), 4) : null,
            'visitPvSevenCompare' => $beforeSevenRow && $beforeSevenRow->getVisitPv() > 0 ? round(($row->getVisitPv() - $beforeSevenRow->getVisitPv()) / $beforeSevenRow->getVisitPv(), 4) : null,
        ];
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramNewUserVisitByDate_{$request->getParams()->get('accountId')}_" . Carbon::parse($request->getParams()->get('date'))->startOfDay();
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

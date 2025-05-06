<?php

namespace WechatMiniProgramStatsBundle\Procedure\DataCube;

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
use WechatMiniProgramStatsBundle\Repository\UserPortraitGendersDataRepository;
use WechatMiniProgramStatsBundle\Service\WechatUserPortraitService;

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序用户画像-性别')]
#[MethodExpose('GetWechatMiniProgramUserPortraitGender')]
class GetWechatMiniProgramUserPortraitGender extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('天，时间范围支持昨天1、最近7天、最近30天')]
    public int $day = 1;

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserPortraitGendersDataRepository $repository,
        private readonly WechatUserPortraitService $service,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $now = Carbon::now();
        $end = $now->clone()->subDay()->endOfDay();
        $start = match ($this->day) {
            1 => $now->clone()->subDay()->startOfDay(),
            7 => $now->clone()->subDays(7)->startOfDay(),
            30 => $now->clone()->subDays(30)->startOfDay(),
            default => throw new ApiException('no data'),
        };
        $date = match ($this->day) {
            1 => $start->format('Ymd'),
            7, 30 => "{$start->format('Ymd')}-{$end->format('Ymd')}",
            default => throw new ApiException('no data'),
        };

        $row = $this->repository->findBy([
            'account' => $account,
            'date' => $date,
            'type' => 'visit_uv',
        ]);
        if (empty($row)) {
            $this->service->getDate($account, $start, $end);
        }

        $list = [];
        foreach ($row as $item) {
            $list[] = [
                'name' => $item->getName(),
                'value' => $item->getValue(),
            ];
        }

        return $list;
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramUserPortraitGender_{$request->getParams()->get('accountId')}_{$request->getParams()->get('day')}";
    }

    protected function getCacheDuration(JsonRpcRequest $request): int
    {
        return HOUR_IN_SECONDS;
    }

    protected function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield null;
    }
}

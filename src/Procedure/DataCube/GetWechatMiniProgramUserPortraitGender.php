<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Procedure\DataCube;

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
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramUserPortraitGenderParam;
use WechatMiniProgramStatsBundle\Repository\UserPortraitGendersDataRepository;
use WechatMiniProgramStatsBundle\Service\WechatUserPortraitService;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序用户画像-性别')]
#[MethodExpose(method: 'GetWechatMiniProgramUserPortraitGender')]
class GetWechatMiniProgramUserPortraitGender extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserPortraitGendersDataRepository $repository,
        private readonly WechatUserPortraitService $service,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramUserPortraitGenderParam $param
     */
    public function execute(GetWechatMiniProgramUserPortraitGenderParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $now = CarbonImmutable::now();
        $end = $now->clone()->subDay()->endOfDay();
        $start = match ($param->day) {
            1 => $now->clone()->subDay()->startOfDay(),
            7 => $now->clone()->subDays(7)->startOfDay(),
            30 => $now->clone()->subDays(30)->startOfDay(),
            default => throw new ApiException('no data'),
        };
        $date = match ($param->day) {
            1 => $start->format('Ymd'),
            7, 30 => "{$start->format('Ymd')}-{$end->format('Ymd')}",
            default => throw new ApiException('no data'),
        };

        /** @var UserPortraitGendersData[] $row */
        $row = $this->repository->findBy([
            'account' => $account,
            'date' => $date,
            'type' => 'visit_uv',
        ]);
        if ([] === $row) {
            $this->service->getDate($account, $start, $end);
        }

        $list = [];
        foreach ($row as $item) {
            $list[] = [
                'name' => $item->getName(),
                'value' => $item->getValue(),
            ];
        }

        return new ArrayResult(['data' => $list]);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            throw new \InvalidArgumentException('Request params cannot be null');
        }

        $accountId = $params->get('accountId');
        $day = $params->get('day');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            throw new \InvalidArgumentException('Account ID must be a string or numeric');
        }
        if (!is_string($day) && !is_numeric($day)) {
            throw new \InvalidArgumentException('Day must be a string or numeric');
        }

        return "GetWechatMiniProgramUserPortraitGender_{$accountId}_{$day}";
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
        yield 'wechat_user_portrait';
    }
}

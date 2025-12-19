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
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramRetainUserByDateParam;
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序留存用户')]
#[MethodExpose(method: 'GetWechatMiniProgramRetainUserByDate')]
class GetWechatMiniProgramRetainUserByDate extends CacheableProcedure
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly DailyRetainDataRepository $repository,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramRetainUserByDateParam $param
     */
    public function execute(GetWechatMiniProgramRetainUserByDateParam|RpcParamInterface $param): ArrayResult
    {
        $account = $this->accountRepository->findOneBy(['id' => $param->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $currentDate = CarbonImmutable::parse($param->date)->startOfDay();
        $currentData = $this->getCurrentRetainData($account, $currentDate);
        $dailyComparisons = $this->calculateDailyComparisons($account, $currentDate, $currentData);
        $weeklyComparisons = $this->calculateWeeklyComparisons($account, $currentDate, $currentData);

        return new ArrayResult([
            ...$currentData,
            ...$dailyComparisons,
            ...$weeklyComparisons,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getCurrentRetainData(Account $account, CarbonImmutable $date): array
    {
        /** @var DailyRetainData|null $visitUv */
        $visitUv = $this->repository->findOneBy([
            'account' => $account,
            'date' => $date,
            'type' => 'visit_uv',
        ]);

        /** @var DailyRetainData|null $visitUvNew */
        $visitUvNew = $this->repository->findOneBy([
            'account' => $account,
            'date' => $date,
            'type' => 'visit_uv_new',
        ]);

        return [
            'visitUv' => (null !== $visitUv) ? (int) $visitUv->getUserNumber() : 0,
            'visitUvNew' => (null !== $visitUvNew) ? (int) $visitUvNew->getUserNumber() : 0,
        ];
    }

    /**
     * @param array<string, mixed> $currentData
     * @return array<string, mixed>
     */
    private function calculateDailyComparisons(Account $account, CarbonImmutable $currentDate, array $currentData): array
    {
        $previousDate = $currentDate->subDay();
        $beforeVisitUv = $this->findRetainData($account, $previousDate, 'visit_uv');
        $beforeVisitUvNew = $this->findRetainData($account, $previousDate, 'visit_uv_new');

        return [
            'visitUvCompare' => $this->calculatePercentageChange($this->ensureInt($currentData['visitUv']), $beforeVisitUv?->getUserNumber()),
            'visitUvNewCompare' => $this->calculatePercentageChange($this->ensureInt($currentData['visitUvNew']), $beforeVisitUvNew?->getUserNumber()),
        ];
    }

    /**
     * @param array<string, mixed> $currentData
     * @return array<string, mixed>
     */
    private function calculateWeeklyComparisons(Account $account, CarbonImmutable $currentDate, array $currentData): array
    {
        $sevenDaysAgo = $currentDate->subDays(7);
        $beforeSevenVisitUv = $this->findRetainData($account, $sevenDaysAgo, 'visit_uv');
        $beforeSevenVisitUvNew = $this->findRetainData($account, $sevenDaysAgo, 'visit_uv_new');

        return [
            'visitUvSevenCompare' => $this->calculatePercentageChange($this->ensureInt($currentData['visitUv']), $beforeSevenVisitUv?->getUserNumber()),
            'visitUvNewSevenCompare' => $this->calculatePercentageChange($this->ensureInt($currentData['visitUvNew']), $beforeSevenVisitUvNew?->getUserNumber()),
        ];
    }

    private function findRetainData(Account $account, CarbonImmutable $date, string $type): ?DailyRetainData
    {
        return $this->repository->findOneBy([
            'account' => $account,
            'date' => $date,
            'type' => $type,
        ]);

        /** @var DailyRetainData|null $result */
    }

    private function ensureInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }

        return 0;
    }

    private function calculatePercentageChange(int $current, mixed $previous): ?float
    {
        if (null === $previous) {
            return null;
        }

        $previousInt = is_numeric($previous) ? (int) $previous : 0;
        if (0 === $previousInt) {
            return null;
        }

        return round(($current - $previousInt) / $previousInt, 4);
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            throw new \InvalidArgumentException('Request params cannot be null');
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $date = $params->get('date');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($date)) {
            return "GetWechatMiniProgramRetainUserByDate_{$accountId}_invalid_date";
        }

        return "GetWechatMiniProgramRetainUserByDate_{$accountId}_" . CarbonImmutable::parse($date)->startOfDay();
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

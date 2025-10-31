<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Procedure\DataCube;

use Carbon\CarbonImmutable;
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

#[Log]
#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取用户访问小程序用户画像性别指定日期内的数据')]
#[MethodExpose(method: 'GetWechatMiniProgramUserPortraitGenderByDateRange')]
class GetWechatMiniProgramUserPortraitGenderByDateRange extends CacheableProcedure
{
    #[MethodParam(description: '小程序ID')]
    public string $accountId = '';

    #[MethodParam(description: '开始日期')]
    public string $startDate = '';

    #[MethodParam(description: '结束日期')]
    public string $endDate = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserPortraitGendersDataRepository $repository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (null === $account) {
            throw new ApiException('找不到小程序');
        }

        $dateArr = $this->generateDateArray();
        $maleRow = $this->getGenderData($account, $dateArr, '男');
        $femaleRow = $this->getGenderData($account, $dateArr, '女');

        return [
            'male' => $this->processGenderResults($maleRow),
            'female' => $this->processGenderResults($femaleRow),
        ];
    }

    /**
     * @return string[]
     */
    private function generateDateArray(): array
    {
        $dateArr = [];
        $date = CarbonImmutable::parse($this->startDate);
        while ($date->lte(CarbonImmutable::parse($this->endDate))) {
            $dateArr[] = $date->format('Ymd');
            $date = $date->addDay();
        }

        return $dateArr;
    }

    /**
     * @param string[] $dateArr
     */
    private function getGenderData(object $account, array $dateArr, string $gender): mixed
    {
        return $this->repository->createQueryBuilder('t')
            ->where('t.account = :account and t.date in (:date) and t.type = :type and t.name = :name')
            ->setParameter('account', $account)
            ->setParameter('date', $dateArr)
            ->setParameter('type', 'visit_uv')
            ->setParameter('name', $gender)
            ->orderBy('t.date')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<array{date: mixed, value: mixed}>
     */
    private function processGenderResults(mixed $results): array
    {
        $processed = [];
        if (is_iterable($results)) {
            foreach ($results as $item) {
                if (is_object($item) && method_exists($item, 'getDate') && method_exists($item, 'getValue')) {
                    $processed[] = [
                        'date' => $item->getDate(),
                        'value' => $item->getValue(),
                    ];
                }
            }
        }

        return $processed;
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $params = $request->getParams();
        if (null === $params) {
            return 'GetWechatMiniProgramUserPortraitGenderByDateRange_default';
        }

        $accountId = $params->get('accountId') ?? 'unknown';
        $startDate = $params->get('startDate');
        $endDate = $params->get('endDate');

        if (!is_string($accountId) && !is_numeric($accountId)) {
            $accountId = 'unknown';
        }

        if (!is_string($startDate) || !is_string($endDate)) {
            return "GetWechatMiniProgramUserPortraitGenderByDateRange_{$accountId}_invalid_dates";
        }

        return "GetWechatMiniProgramUserPortraitGenderByDateRange_{$accountId}_" .
            CarbonImmutable::parse($startDate)->startOfDay() . '_' . CarbonImmutable::parse($endDate)->startOfDay();
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

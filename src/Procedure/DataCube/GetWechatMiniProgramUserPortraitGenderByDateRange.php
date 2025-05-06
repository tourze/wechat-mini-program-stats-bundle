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

#[Log]
#[MethodTag('微信小程序')]
#[MethodDoc('获取用户访问小程序用户画像性别指定日期内的数据')]
#[MethodExpose('GetWechatMiniProgramUserPortraitGenderByDateRange')]
class GetWechatMiniProgramUserPortraitGenderByDateRange extends CacheableProcedure
{
    #[MethodParam('小程序ID')]
    public string $accountId = '';

    #[MethodParam('开始日期')]
    public string $startDate = '';

    #[MethodParam('结束日期')]
    public string $endDate = '';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserPortraitGendersDataRepository $repository,
    ) {
    }

    public function execute(): array
    {
        $account = $this->accountRepository->findOneBy(['id' => $this->accountId, 'valid' => true]);
        if (!$account) {
            throw new ApiException('找不到小程序');
        }

        $dateArr = [];
        $date = Carbon::parse($this->startDate);
        while ($date->lte(Carbon::parse($this->endDate))) {
            $dateArr[] = $date->format('Ymd');
            $date = $date->addDay();
        }

        $maleRow = $this->repository->createQueryBuilder('t')
            ->where('t.account = :account and t.date in (:date) and t.type = :type and t.name = :name')
            ->setParameter('account', $account)
            ->setParameter('date', $dateArr)
            ->setParameter('type', 'visit_uv')
            ->setParameter('name', '男')
            ->orderBy('t.date')
            ->getQuery()
            ->getResult();

        $femaleRow = $this->repository->createQueryBuilder('t')
            ->where('t.account = :account and t.date in (:date) and t.type = :type and t.name = :name')
            ->setParameter('account', $account)
            ->setParameter('date', $dateArr)
            ->setParameter('type', 'visit_uv')
            ->setParameter('name', '女')
            ->orderBy('t.date')
            ->getQuery()
            ->getResult();

        $male = [];
        foreach ($maleRow as $item) {
            $male[] = [
                'date' => $item->getDate(),
                'value' => $item->getValue(),
            ];
        }

        $female = [];
        foreach ($femaleRow as $item) {
            $female[] = [
                'date' => $item->getDate(),
                'value' => $item->getValue(),
            ];
        }

        return [
            'male' => $male,
            'female' => $female,
        ];
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        return "GetWechatMiniProgramUserPortraitGenderByDateRange_{$request->getParams()->get('accountId')}_" .
            Carbon::parse($request->getParams()->get('startDate'))->startOfDay() . '_' . Carbon::parse($request->getParams()->get('endDate'))->startOfDay();
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

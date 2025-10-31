<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Service;

use Carbon\CarbonInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;
use WechatMiniProgramStatsBundle\Interface\UserPortraitEntityInterface;
use WechatMiniProgramStatsBundle\Repository\UserPortraitAgeDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitCityDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDeviceDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitGendersDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitPlatformDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitProvinceDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatUserPortraitRequest;

#[WithMonologChannel(channel: 'wechat_mini_program_stats')]
class WechatUserPortraitService
{
    public function __construct(
        private readonly UserPortraitProvinceDataRepository $provinceRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPortraitCityDataRepository $cityRepository,
        private readonly UserPortraitGendersDataRepository $gendersRepository,
        private readonly UserPortraitPlatformDataRepository $platformRepository,
        private readonly UserPortraitDeviceDataRepository $deviceRepository,
        private readonly UserPortraitAgeDataRepository $ageRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getDate(Account $account, CarbonInterface $start, CarbonInterface $end): void
    {
        $request = new GetWechatUserPortraitRequest();
        $request->setAccount($account);
        $request->setBeginDate($start);
        $request->setEndDate($end);

        try {
            $res = $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('获取用户用户画像分布时发生异常', [
                'account' => $account,
                'exception' => $exception,
            ]);

            return;
        }

        if (!is_array($res) || !isset($res['ref_date']) || !is_string($res['ref_date'])) {
            return;
        }

        if (isset($res['visit_uv_new']) && is_array($res['visit_uv_new'])) {
            /** @var array<string, array<int, array<string, mixed>>> $visitUvNew */
            $visitUvNew = $res['visit_uv_new'];
            $this->processUserPortraitData($visitUvNew, $res['ref_date'], $account, 'visit_uv_new');
        }

        if (isset($res['visit_uv']) && is_array($res['visit_uv'])) {
            /** @var array<string, array<int, array<string, mixed>>> $visitUv */
            $visitUv = $res['visit_uv'];
            $this->processUserPortraitData($visitUv, $res['ref_date'], $account, 'visit_uv');
        }
    }

    /**
     * @param array<string, array<int, array<string, mixed>>> $data
     */
    private function processUserPortraitData(array $data, string $refDate, Account $account, string $type): void
    {
        $processors = [
            'province' => [$this->provinceRepository, UserPortraitProvinceData::class],
            'city' => [$this->cityRepository, UserPortraitCityData::class],
            'genders' => [$this->gendersRepository, UserPortraitGendersData::class],
            'platforms' => [$this->platformRepository, UserPortraitPlatformData::class],
            'devices' => [$this->deviceRepository, UserPortraitDeviceData::class],
            'ages' => [$this->ageRepository, UserPortraitAgeData::class],
        ];

        foreach ($processors as $key => [$repository, $entityClass]) {
            if (!isset($data[$key]) || !is_array($data[$key])) {
                continue;
            }

            foreach ($data[$key] as $value) {
                if (is_array($value)) {
                    /** @var array<string, mixed> $value */
                    $this->processPortraitEntity($repository, $entityClass, $value, $refDate, $account, $type);
                }
            }
        }
    }

    /**
     * @param UserPortraitProvinceDataRepository|UserPortraitCityDataRepository|UserPortraitGendersDataRepository|UserPortraitPlatformDataRepository|UserPortraitDeviceDataRepository|UserPortraitAgeDataRepository $repository
     * @param class-string<UserPortraitEntityInterface> $entityClass
     * @param array<string, mixed> $value
     */
    private function processPortraitEntity(object $repository, string $entityClass, array $value, string $refDate, Account $account, string $type): void
    {
        $name = $value['name'] ?? null;
        if (!is_string($name)) {
            return;
        }

        /** @var UserPortraitAgeData|UserPortraitCityData|UserPortraitDeviceData|UserPortraitGendersData|UserPortraitPlatformData|UserPortraitProvinceData|null $entity */
        $entity = $repository->findOneBy([
            'date' => $refDate,
            'account' => $account,
            'name' => $name,
            'type' => $type,
        ]);

        if (null === $entity) {
            /** @var UserPortraitEntityInterface $entity */
            $entity = new $entityClass();
            $entity->setAccount($account);
            $entity->setType($type);
            $entity->setDate($refDate);
            $entity->setName($name);
        }

        $valueId = isset($value['id']) && (is_string($value['id']) || is_null($value['id'])) ? $value['id'] : null;
        $entityValue = isset($value['value']) && (is_string($value['value']) || is_null($value['value'])) ? $value['value'] : null;

        $entity->setValueId($valueId);
        $entity->setValue($entityValue);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->detach($entity);
    }
}

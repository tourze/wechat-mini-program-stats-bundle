<?php

namespace WechatMiniProgramStatsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitAgeDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitCityDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDeviceDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitGendersDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitPlatformDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitProvinceDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatUserPortraitRequest;

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

    public function getDate(Account $account, $start, $end)
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
        if ((bool) isset($res['visit_uv_new'])) {
            foreach ($res['visit_uv_new']['province'] as $provinceValue) {
                $provinceData = $this->provinceRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $provinceValue['name'],
                    'type' => 'visit_uv_new',
                ]);
                if (!$provinceData) {
                    $provinceData = new UserPortraitProvinceData();
                    $provinceData->setAccount($account);
                    $provinceData->setType('visit_uv_new');
                    $provinceData->setDate($res['ref_date']);
                    $provinceData->setName($provinceValue['name']);
                }
                $valueId = '';
                if ((bool) isset($provinceValue['id'])) {
                    $valueId = $provinceValue['id'];
                }
                $provinceData->setValueId($valueId);
                $provinceData->setValue($provinceValue['value']);
                $this->entityManager->persist($provinceData);
                $this->entityManager->flush();
                $this->entityManager->detach($provinceData);
            }

            foreach ($res['visit_uv_new']['city'] as $cityValue) {
                $cityData = $this->cityRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $cityValue['name'],
                    'type' => 'visit_uv_new',
                ]);
                if (!$cityData) {
                    $cityData = new UserPortraitCityData();
                    $cityData->setAccount($account);
                    $cityData->setName($cityValue['name']);
                    $cityData->setType('visit_uv_new');
                    $cityData->setDate($res['ref_date']);
                }
                $valueId = '';
                if ((bool) isset($cityValue['id'])) {
                    $valueId = $cityValue['id'];
                }
                $cityData->setValueId($valueId);
                $cityData->setValue($cityValue['value']);
                $this->entityManager->persist($cityData);
                $this->entityManager->flush();
                $this->entityManager->detach($cityData);
            }

            foreach ($res['visit_uv_new']['genders'] as $genderValue) {
                $genderData = $this->gendersRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $genderValue['name'],
                    'type' => 'visit_uv_new',
                ]);
                if (!$genderData) {
                    $genderData = new UserPortraitGendersData();
                    $genderData->setAccount($account);
                    $genderData->setType('visit_uv_new');
                    $genderData->setDate($res['ref_date']);
                    $genderData->setName($genderValue['name']);
                }
                $valueId = '';
                if ((bool) isset($genderValue['id'])) {
                    $valueId = $genderValue['id'];
                }
                $genderData->setValueId($valueId);
                $genderData->setValue($genderValue['value']);
                $this->entityManager->persist($genderData);
                $this->entityManager->flush();
                $this->entityManager->detach($genderData);
            }

            foreach ($res['visit_uv_new']['platforms'] as $platformValue) {
                $platformData = $this->platformRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $platformValue['name'],
                    'type' => 'visit_uv_new',
                ]);
                if (!$platformData) {
                    $platformData = new UserPortraitPlatformData();
                    $platformData->setAccount($account);
                    $platformData->setType('visit_uv_new');
                    $platformData->setDate($res['ref_date']);
                    $platformData->setName($platformValue['name']);
                }
                $valueId = '';
                if ((bool) isset($platformValue['id'])) {
                    $valueId = $platformValue['id'];
                }
                $platformData->setValueId($valueId);
                $platformData->setValue($platformValue['value']);
                $this->entityManager->persist($platformData);
                $this->entityManager->flush();
                $this->entityManager->detach($platformData);
            }

            foreach ($res['visit_uv_new']['devices'] as $deviceValue) {
                $deviceData = $this->deviceRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $deviceValue['name'],
                    'type' => 'visit_uv_new',
                ]);
                if (!$deviceData) {
                    $deviceData = new UserPortraitDeviceData();
                    $deviceData->setAccount($account);
                    $deviceData->setType('visit_uv_new');
                    $deviceData->setDate($res['ref_date']);
                    $deviceData->setName($deviceValue['name']);
                }
                $valueId = '';
                if ((bool) isset($deviceValue['id'])) {
                    $valueId = $deviceValue['id'];
                }
                $deviceData->setValueId($valueId);
                $deviceData->setValue($deviceValue['value']);
                $this->entityManager->persist($deviceData);
                $this->entityManager->flush();
                $this->entityManager->detach($deviceData);
            }

            foreach ($res['visit_uv_new']['ages'] as $ageValue) {
                $ageData = $this->ageRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $ageValue['name'],
                    'type' => 'visit_uv_new',
                ]);
                if (!$ageData) {
                    $ageData = new UserPortraitAgeData();
                    $ageData->setAccount($account);
                    $ageData->setType('visit_uv_new');
                    $ageData->setDate($res['ref_date']);
                    $ageData->setName($ageValue['name']);
                }
                $valueId = '';
                if ((bool) isset($ageValue['id'])) {
                    $valueId = $ageValue['id'];
                }
                $ageData->setValueId($valueId);
                $ageData->setValue($ageValue['value']);
                $this->entityManager->persist($ageData);
                $this->entityManager->flush();
                $this->entityManager->detach($ageData);
            }
        }
        if ((bool) isset($res['visit_uv'])) {
            foreach ($res['visit_uv']['province'] as $provinceValue) {
                $provinceData = $this->provinceRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'name' => $provinceValue['name'],
                    'type' => 'visit_uv',
                ]);
                if (!$provinceData) {
                    $provinceData = new UserPortraitProvinceData();
                    $provinceData->setAccount($account);
                    $provinceData->setType('visit_uv');
                    $provinceData->setDate($res['ref_date']);
                    $provinceData->setName($provinceValue['name']);
                }
                $valueId = '';
                if ((bool) isset($provinceValue['id'])) {
                    $valueId = $provinceValue['id'];
                }
                $provinceData->setValueId($valueId);
                $provinceData->setValue($provinceValue['value']);
                $this->entityManager->persist($provinceData);
                $this->entityManager->flush();
                $this->entityManager->detach($provinceData);
            }

            foreach ($res['visit_uv']['city'] as $cityValue) {
                $cityData = $this->cityRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'type' => 'visit_uv',
                    'name' => $cityValue['name'],
                ]);
                if (!$cityData) {
                    $cityData = new UserPortraitCityData();
                    $cityData->setAccount($account);
                    $cityData->setType('visit_uv');
                    $cityData->setDate($res['ref_date']);
                    $cityData->setName($cityValue['name']);
                }
                $valueId = '';
                if ((bool) isset($cityValue['id'])) {
                    $valueId = $cityValue['id'];
                }
                $cityData->setValueId($valueId);
                $cityData->setValue($cityValue['value']);
                $this->entityManager->persist($cityData);
                $this->entityManager->flush();
                $this->entityManager->detach($cityData);
            }

            foreach ($res['visit_uv']['genders'] as $genderValue) {
                $genderData = $this->gendersRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'type' => 'visit_uv',
                    'name' => $genderValue['name'],
                ]);
                if (!$genderData) {
                    $genderData = new UserPortraitGendersData();
                    $genderData->setAccount($account);
                    $genderData->setType('visit_uv');
                    $genderData->setDate($res['ref_date']);
                    $genderData->setName($genderValue['name']);
                }
                $valueId = '';
                if ((bool) isset($genderValue['id'])) {
                    $valueId = $genderValue['id'];
                }
                $genderData->setValueId($valueId);
                $genderData->setValue($genderValue['value']);
                $this->entityManager->persist($genderData);
                $this->entityManager->flush();
                $this->entityManager->detach($genderData);
            }

            foreach ($res['visit_uv']['platforms'] as $platformValue) {
                $platformData = $this->platformRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'type' => 'visit_uv',
                    'name' => $platformValue['name'],
                ]);
                if (!$platformData) {
                    $platformData = new UserPortraitPlatformData();
                    $platformData->setAccount($account);
                    $platformData->setType('visit_uv');
                    $platformData->setDate($res['ref_date']);
                    $platformData->setName($platformValue['name']);
                }
                $valueId = '';
                if ((bool) isset($platformValue['id'])) {
                    $valueId = $platformValue['id'];
                }
                $platformData->setValueId($valueId);
                $platformData->setValue($platformValue['value']);
                $this->entityManager->persist($platformData);
                $this->entityManager->flush();
                $this->entityManager->detach($platformData);
            }

            foreach ($res['visit_uv']['devices'] as $deviceValue) {
                $deviceData = $this->deviceRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'type' => 'visit_uv',
                ]);
                if (!$deviceData) {
                    $deviceData = new UserPortraitDeviceData();
                    $deviceData->setAccount($account);
                    $deviceData->setType('visit_uv');
                    $deviceData->setDate($res['ref_date']);
                }
                $valueId = '';
                if ((bool) isset($deviceValue['id'])) {
                    $valueId = $deviceValue['id'];
                }
                $deviceData->setValueId($valueId);
                $deviceData->setName($deviceValue['name']);
                $deviceData->setValue($deviceValue['value']);
                $this->entityManager->persist($deviceData);
                $this->entityManager->flush();
                $this->entityManager->detach($deviceData);
            }

            foreach ($res['visit_uv']['ages'] as $ageValue) {
                $ageData = $this->ageRepository->findOneBy([
                    'date' => $res['ref_date'],
                    'account' => $account,
                    'type' => 'visit_uv',
                    'name' => $ageValue['name'],
                ]);
                if (!$ageData) {
                    $ageData = new UserPortraitAgeData();
                    $ageData->setAccount($account);
                    $ageData->setType('visit_uv');
                    $ageData->setDate($res['ref_date']);
                    $ageData->setName($ageValue['name']);
                }
                $valueId = '';
                if ((bool) isset($ageValue['id'])) {
                    $valueId = $ageValue['id'];
                }
                $ageData->setValueId($valueId);
                $ageData->setValue($ageValue['value']);
                $this->entityManager->persist($ageData);
                $this->entityManager->flush();
                $this->entityManager->detach($ageData);
            }
        }
    }
}

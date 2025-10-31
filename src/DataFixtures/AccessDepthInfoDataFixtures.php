<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;

class AccessDepthInfoDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $accessDepthInfoData = new AccessDepthInfoData();
        $accessDepthInfoData->setDate(new \DateTimeImmutable('2024-01-01'));
        $accessDepthInfoData->setDataKey('test-key');
        $accessDepthInfoData->setDataValue('test-value');

        $manager->persist($accessDepthInfoData);
        $manager->flush();
    }
}

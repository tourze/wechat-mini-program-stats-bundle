<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;

class AccessStayTimeInfoDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $accessStayTimeInfoData = new AccessStayTimeInfoData();
        $accessStayTimeInfoData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($accessStayTimeInfoData);
        $manager->flush();
    }
}

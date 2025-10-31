<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\VisitDistributionData;

class VisitDistributionDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $visitDistributionData = new VisitDistributionData();
        $visitDistributionData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($visitDistributionData);
        $manager->flush();
    }
}

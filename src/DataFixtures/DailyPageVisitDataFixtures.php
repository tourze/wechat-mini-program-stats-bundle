<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;

class DailyPageVisitDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dailyPageVisitData = new DailyPageVisitData();
        $dailyPageVisitData->setDate(new \DateTimeImmutable('2024-01-01'));
        $dailyPageVisitData->setPage('/pages/index/index');
        $dailyPageVisitData->setVisitPv(100);
        $dailyPageVisitData->setVisitUv(80);
        $dailyPageVisitData->setNewUserVisitPv(20);

        $manager->persist($dailyPageVisitData);
        $manager->flush();
    }
}

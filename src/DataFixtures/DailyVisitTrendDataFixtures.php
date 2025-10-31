<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;

class DailyVisitTrendDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dailyVisitTrendData = new DailyVisitTrendData();
        $dailyVisitTrendData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($dailyVisitTrendData);
        $manager->flush();
    }
}

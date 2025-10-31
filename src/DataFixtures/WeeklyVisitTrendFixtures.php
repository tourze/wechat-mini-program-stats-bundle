<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;

class WeeklyVisitTrendFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $weeklyVisitTrend = new WeeklyVisitTrend();
        $weeklyVisitTrend->setBeginDate(new \DateTimeImmutable('2024-01-01'));
        $weeklyVisitTrend->setEndDate(new \DateTimeImmutable('2024-01-07'));

        $manager->persist($weeklyVisitTrend);
        $manager->flush();
    }
}

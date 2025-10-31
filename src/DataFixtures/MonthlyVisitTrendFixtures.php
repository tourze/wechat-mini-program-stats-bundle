<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;

class MonthlyVisitTrendFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $monthlyVisitTrend = new MonthlyVisitTrend();
        $monthlyVisitTrend->setBeginDate(new \DateTimeImmutable('2024-01-01'));
        $monthlyVisitTrend->setEndDate(new \DateTimeImmutable('2024-01-31'));

        $manager->persist($monthlyVisitTrend);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;

class DailyNewUserVisitPvFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dailyNewUserVisitPv = new DailyNewUserVisitPv();
        $dailyNewUserVisitPv->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($dailyNewUserVisitPv);
        $manager->flush();
    }
}

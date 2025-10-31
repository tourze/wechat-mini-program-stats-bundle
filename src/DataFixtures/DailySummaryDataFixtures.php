<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;

class DailySummaryDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dailySummaryData = new DailySummaryData();
        $dailySummaryData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($dailySummaryData);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;

class DailyRetainDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dailyRetainData = new DailyRetainData();
        $dailyRetainData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($dailyRetainData);
        $manager->flush();
    }
}

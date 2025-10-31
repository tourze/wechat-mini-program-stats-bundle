<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\PerformanceData;

class PerformanceDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $performanceData = new PerformanceData();
        $performanceData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($performanceData);
        $manager->flush();
    }
}

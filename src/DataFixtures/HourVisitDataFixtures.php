<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;

class HourVisitDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $hourVisitData = new HourVisitData();
        $hourVisitData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($hourVisitData);
        $manager->flush();
    }
}

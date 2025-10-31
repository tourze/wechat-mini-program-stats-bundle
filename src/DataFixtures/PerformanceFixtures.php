<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\Performance;

class PerformanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $performance = new Performance();

        $manager->persist($performance);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;

class OperationPerformanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $operationPerformance = new OperationPerformance();
        $operationPerformance->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($operationPerformance);
        $manager->flush();
    }
}

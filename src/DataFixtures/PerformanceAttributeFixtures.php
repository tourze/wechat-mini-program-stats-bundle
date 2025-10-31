<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\Performance;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;

class PerformanceAttributeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $performance = new Performance();
        $manager->persist($performance);

        $performanceAttribute = new PerformanceAttribute();
        $performanceAttribute->setPerformance($performance);
        $performanceAttribute->setName('test-attribute');
        $performanceAttribute->setValue('test-value');

        $manager->persist($performanceAttribute);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PerformanceFixtures::class,
        ];
    }
}

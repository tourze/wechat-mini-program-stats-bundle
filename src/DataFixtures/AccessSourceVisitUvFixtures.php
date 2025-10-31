<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;

class AccessSourceVisitUvFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $accessSourceVisitUv = new AccessSourceVisitUv();
        $accessSourceVisitUv->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($accessSourceVisitUv);
        $manager->flush();
    }
}

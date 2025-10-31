<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;

class AccessSourceSessionCntFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $accessSourceSessionCnt = new AccessSourceSessionCnt();
        $accessSourceSessionCnt->setDate(new \DateTimeImmutable('2024-01-01'));
        $accessSourceSessionCnt->setDataKey('test-fixtures-key');
        $accessSourceSessionCnt->setDataValue('test-fixtures-value');

        $manager->persist($accessSourceSessionCnt);
        $manager->flush();
    }
}

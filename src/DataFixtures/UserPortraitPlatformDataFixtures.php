<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;

class UserPortraitPlatformDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userPortraitPlatformData = new UserPortraitPlatformData();
        $userPortraitPlatformData->setDate('2024-01-01');

        $manager->persist($userPortraitPlatformData);
        $manager->flush();
    }
}

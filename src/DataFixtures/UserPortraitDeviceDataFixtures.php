<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;

class UserPortraitDeviceDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userPortraitDeviceData = new UserPortraitDeviceData();
        $userPortraitDeviceData->setDate('2024-01-01');

        $manager->persist($userPortraitDeviceData);
        $manager->flush();
    }
}

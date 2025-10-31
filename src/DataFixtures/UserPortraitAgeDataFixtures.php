<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;

class UserPortraitAgeDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userPortraitAgeData = new UserPortraitAgeData();
        $userPortraitAgeData->setDate('2024-01-01');

        $manager->persist($userPortraitAgeData);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;

class UserPortraitGendersDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userPortraitGendersData = new UserPortraitGendersData();
        $userPortraitGendersData->setDate('2024-01-01');

        $manager->persist($userPortraitGendersData);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;

class UserPortraitCityDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userPortraitCityData = new UserPortraitCityData();
        $userPortraitCityData->setDate('2024-01-01');

        $manager->persist($userPortraitCityData);
        $manager->flush();
    }
}

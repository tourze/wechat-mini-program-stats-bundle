<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;

class UserPortraitDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userPortraitData = new UserPortraitData();
        $userPortraitData->setDate('2024-01-01');

        $manager->persist($userPortraitData);
        $manager->flush();
    }
}

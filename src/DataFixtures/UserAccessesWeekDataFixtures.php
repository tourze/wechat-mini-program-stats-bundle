<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;

class UserAccessesWeekDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userAccessesWeekData = new UserAccessesWeekData();
        $userAccessesWeekData->setDate('2024-01-01');

        $manager->persist($userAccessesWeekData);
        $manager->flush();
    }
}

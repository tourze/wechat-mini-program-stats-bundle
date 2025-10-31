<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;

class UserAccessesMonthDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userAccessesMonthData = new UserAccessesMonthData();
        $userAccessesMonthData->setDate('2024-01-01');

        $manager->persist($userAccessesMonthData);
        $manager->flush();
    }
}

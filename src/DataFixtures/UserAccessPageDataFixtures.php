<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;

class UserAccessPageDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userAccessPageData = new UserAccessPageData();
        $userAccessPageData->setDate(new \DateTimeImmutable('2024-01-01'));

        $manager->persist($userAccessPageData);
        $manager->flush();
    }
}

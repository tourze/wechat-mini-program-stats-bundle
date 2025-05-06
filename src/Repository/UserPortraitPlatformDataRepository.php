<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;

/**
 * @method UserPortraitPlatformData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitPlatformData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitPlatformData[]    findAll()
 * @method UserPortraitPlatformData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitPlatformDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitPlatformData::class);
    }
}

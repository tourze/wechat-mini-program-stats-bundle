<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;

/**
 * @method UserPortraitDeviceData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitDeviceData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitDeviceData[]    findAll()
 * @method UserPortraitDeviceData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitDeviceDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitDeviceData::class);
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;

/**
 * @method UserPortraitProvinceData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitProvinceData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitProvinceData[]    findAll()
 * @method UserPortraitProvinceData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitProvinceDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitProvinceData::class);
    }
}

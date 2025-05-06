<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;

/**
 * @method AccessDepthInfoData|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessDepthInfoData|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessDepthInfoData[]    findAll()
 * @method AccessDepthInfoData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessDepthInfoDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessDepthInfoData::class);
    }
}

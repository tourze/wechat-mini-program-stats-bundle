<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\VisitDistributionData;

/**
 * @method VisitDistributionData|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitDistributionData|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitDistributionData[]    findAll()
 * @method VisitDistributionData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitDistributionDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitDistributionData::class);
    }
}

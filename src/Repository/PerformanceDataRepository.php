<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\PerformanceData;

/**
 * @method PerformanceData|null find($id, $lockMode = null, $lockVersion = null)
 * @method PerformanceData|null findOneBy(array $criteria, array $orderBy = null)
 * @method PerformanceData[]    findAll()
 * @method PerformanceData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerformanceDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PerformanceData::class);
    }
}

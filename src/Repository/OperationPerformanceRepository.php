<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;

/**
 * @method OperationPerformance|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationPerformance|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationPerformance[]    findAll()
 * @method OperationPerformance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationPerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationPerformance::class);
    }
}

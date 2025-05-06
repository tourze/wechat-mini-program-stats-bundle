<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;

/**
 * @method PerformanceAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method PerformanceAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method PerformanceAttribute[]    findAll()
 * @method PerformanceAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerformanceAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PerformanceAttribute::class);
    }
}

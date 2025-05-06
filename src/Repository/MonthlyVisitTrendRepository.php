<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;

/**
 * @method MonthlyVisitTrend|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonthlyVisitTrend|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonthlyVisitTrend[]    findAll()
 * @method MonthlyVisitTrend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthlyVisitTrendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthlyVisitTrend::class);
    }
}

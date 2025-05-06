<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;

/**
 * @method WeeklyVisitTrend|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeeklyVisitTrend|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeeklyVisitTrend[]    findAll()
 * @method WeeklyVisitTrend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeeklyVisitTrendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeeklyVisitTrend::class);
    }
}

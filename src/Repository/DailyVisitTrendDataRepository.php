<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;

/**
 * @method DailyVisitTrendData|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyVisitTrendData|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyVisitTrendData[]    findAll()
 * @method DailyVisitTrendData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyVisitTrendDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyVisitTrendData::class);
    }
}

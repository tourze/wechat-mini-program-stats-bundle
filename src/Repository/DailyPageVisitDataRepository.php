<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;

/**
 * @method DailyPageVisitData|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyPageVisitData|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyPageVisitData[]    findAll()
 * @method DailyPageVisitData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyPageVisitDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyPageVisitData::class);
    }
}

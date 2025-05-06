<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;

/**
 * @method DailyRetainData|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyRetainData|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyRetainData[]    findAll()
 * @method DailyRetainData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyRetainDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyRetainData::class);
    }
}

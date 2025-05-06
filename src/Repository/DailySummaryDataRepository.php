<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;

/**
 * @method DailySummaryData|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailySummaryData|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailySummaryData[]    findAll()
 * @method DailySummaryData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailySummaryDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailySummaryData::class);
    }
}

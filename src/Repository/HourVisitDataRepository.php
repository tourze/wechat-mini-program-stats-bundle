<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;

/**
 * @method HourVisitData|null find($id, $lockMode = null, $lockVersion = null)
 * @method HourVisitData|null findOneBy(array $criteria, array $orderBy = null)
 * @method HourVisitData[]    findAll()
 * @method HourVisitData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HourVisitDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HourVisitData::class);
    }
}

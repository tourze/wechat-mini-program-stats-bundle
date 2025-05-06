<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;

/**
 * @method DailyNewUserVisitPv|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyNewUserVisitPv|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyNewUserVisitPv[]    findAll()
 * @method DailyNewUserVisitPv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyNewUserVisitPvRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyNewUserVisitPv::class);
    }
}

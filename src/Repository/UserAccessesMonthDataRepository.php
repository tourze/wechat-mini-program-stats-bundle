<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;

/**
 * @method UserAccessesMonthData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccessesMonthData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccessesMonthData[]    findAll()
 * @method UserAccessesMonthData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAccessesMonthDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccessesMonthData::class);
    }
}

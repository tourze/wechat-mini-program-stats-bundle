<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;

/**
 * @method UserAccessesWeekData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccessesWeekData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccessesWeekData[]    findAll()
 * @method UserAccessesWeekData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAccessesWeekDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccessesWeekData::class);
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;

/**
 * @method AccessSourceSessionCnt|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessSourceSessionCnt|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessSourceSessionCnt[]    findAll()
 * @method AccessSourceSessionCnt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessSourceSessionCntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessSourceSessionCnt::class);
    }
}

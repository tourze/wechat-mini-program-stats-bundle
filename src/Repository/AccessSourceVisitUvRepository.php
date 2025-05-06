<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;

/**
 * @method AccessSourceVisitUv|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessSourceVisitUv|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessSourceVisitUv[]    findAll()
 * @method AccessSourceVisitUv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessSourceVisitUvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessSourceVisitUv::class);
    }
}

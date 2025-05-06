<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;

/**
 * @method UserAccessPageData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccessPageData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccessPageData[]    findAll()
 * @method UserAccessPageData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAccessPageDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccessPageData::class);
    }
}

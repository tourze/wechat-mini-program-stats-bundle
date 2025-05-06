<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;

/**
 * @method UserPortraitGendersData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitGendersData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitGendersData[]    findAll()
 * @method UserPortraitGendersData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitGendersDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitGendersData::class);
    }
}

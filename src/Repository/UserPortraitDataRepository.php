<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;

/**
 * @method UserPortraitData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitData[]    findAll()
 * @method UserPortraitData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitData::class);
    }
}

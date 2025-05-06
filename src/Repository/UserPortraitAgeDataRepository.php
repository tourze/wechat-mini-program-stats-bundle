<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;

/**
 * @method UserPortraitAgeData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitAgeData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitAgeData[]    findAll()
 * @method UserPortraitAgeData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitAgeDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitAgeData::class);
    }
}

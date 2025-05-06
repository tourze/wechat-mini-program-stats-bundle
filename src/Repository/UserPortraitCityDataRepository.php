<?php

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;

/**
 * @method UserPortraitCityData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPortraitCityData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPortraitCityData[]    findAll()
 * @method UserPortraitCityData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPortraitCityDataRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitCityData::class);
    }
}

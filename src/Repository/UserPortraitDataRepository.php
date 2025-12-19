<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;

/**
 * @extends ServiceEntityRepository<UserPortraitData>
 */
#[AsRepository(entityClass: UserPortraitData::class)]
final class UserPortraitDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitData::class);
    }

    public function save(UserPortraitData $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserPortraitData $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

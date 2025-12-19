<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;

/**
 * @extends ServiceEntityRepository<UserPortraitDeviceData>
 */
#[AsRepository(entityClass: UserPortraitDeviceData::class)]
final class UserPortraitDeviceDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPortraitDeviceData::class);
    }

    public function save(UserPortraitDeviceData $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserPortraitDeviceData $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

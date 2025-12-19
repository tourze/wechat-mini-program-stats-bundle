<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;

/**
 * @extends ServiceEntityRepository<UserAccessesWeekData>
 */
#[AsRepository(entityClass: UserAccessesWeekData::class)]
final class UserAccessesWeekDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccessesWeekData::class);
    }

    public function save(UserAccessesWeekData $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserAccessesWeekData $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

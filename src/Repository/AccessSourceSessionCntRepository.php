<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;

/**
 * @extends ServiceEntityRepository<AccessSourceSessionCnt>
 */
#[AsRepository(entityClass: AccessSourceSessionCnt::class)]
final class AccessSourceSessionCntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessSourceSessionCnt::class);
    }

    public function save(AccessSourceSessionCnt $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AccessSourceSessionCnt $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

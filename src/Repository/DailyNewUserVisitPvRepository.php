<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;

/**
 * @extends ServiceEntityRepository<DailyNewUserVisitPv>
 */
#[AsRepository(entityClass: DailyNewUserVisitPv::class)]
final class DailyNewUserVisitPvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyNewUserVisitPv::class);
    }

    public function save(DailyNewUserVisitPv $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DailyNewUserVisitPv $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

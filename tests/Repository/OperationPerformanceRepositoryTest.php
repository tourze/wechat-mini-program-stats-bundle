<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;
use WechatMiniProgramStatsBundle\Repository\OperationPerformanceRepository;

/**
 * @internal
 */
#[CoversClass(OperationPerformanceRepository::class)]
#[RunTestsInSeparateProcesses]
final class OperationPerformanceRepositoryTest extends AbstractRepositoryTestCase
{
    private OperationPerformanceRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(OperationPerformanceRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(OperationPerformanceRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new OperationPerformance();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test OperationPerformanceRepository ' . uniqid());
    }

    /**
     * @return OperationPerformanceRepository
     */
    protected function getRepository(): OperationPerformanceRepository
    {
        return self::getService(OperationPerformanceRepository::class);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createValidEntity();

        $this->repository->save($entity);

        self::assertGreaterThan(0, $entity->getId());
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createValidEntity();
        $this->repository->save($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    public function testFindByWithNullAccountShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setAccount(null);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testCountWithNullAccountShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setAccount(null);
        $this->repository->save($entity);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithNullDateFieldShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setDate(null);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['date' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testCountWithNullDateFieldShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setDate(null);
        $this->repository->save($entity);

        $count = $this->repository->count(['date' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByWithOrderBy(): void
    {
        $entity1 = $this->createValidEntity();
        $entity1->setCostTime('300');
        $entity1->setCostTimeType('network-high-' . uniqid());
        $this->repository->save($entity1, false);

        $entity2 = $this->createValidEntity();
        $entity2->setCostTime('100');
        $entity2->setCostTimeType('network-low-' . uniqid());
        $this->repository->save($entity2, false);
        self::getEntityManager()->flush();

        $result = $this->repository->findOneBy(['costTimeType' => $entity2->getCostTimeType()]);
        self::assertNotNull($result);

        self::assertEquals('100', $result->getCostTime());
    }

    private function createValidEntity(): OperationPerformance
    {
        $entity = new OperationPerformance();
        $entity->setDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setCostTimeType('network');
        $entity->setCostTime('150');

        return $entity;
    }
}

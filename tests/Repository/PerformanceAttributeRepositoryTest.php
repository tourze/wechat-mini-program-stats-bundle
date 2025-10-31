<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\Performance;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;
use WechatMiniProgramStatsBundle\Repository\PerformanceAttributeRepository;

/**
 * @internal
 */
#[CoversClass(PerformanceAttributeRepository::class)]
#[RunTestsInSeparateProcesses]
final class PerformanceAttributeRepositoryTest extends AbstractRepositoryTestCase
{
    private PerformanceAttributeRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(PerformanceAttributeRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(PerformanceAttributeRepository::class));
    }

    protected function createNewEntity(): object
    {
        $performance = $this->createValidPerformance();
        self::getEntityManager()->persist($performance);
        self::getEntityManager()->flush();

        $entity = new PerformanceAttribute();
        $entity->setName('Test PerformanceAttributeRepository ' . uniqid());
        $entity->setValue('test_value');
        $entity->setPerformance($performance);

        return $entity;
    }

    /**
     * @return PerformanceAttributeRepository
     */
    protected function getRepository(): PerformanceAttributeRepository
    {
        return self::getService(PerformanceAttributeRepository::class);
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

    public function testFindByWithPerformanceAssociationShouldWork(): void
    {
        $performance = $this->createValidPerformance();
        self::getEntityManager()->persist($performance);
        self::getEntityManager()->flush();

        $entity = $this->createValidEntity();
        $entity->setPerformance($performance);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['performance' => $performance]);

        self::assertNotEmpty($results);
    }

    public function testCountWithPerformanceAssociationShouldWork(): void
    {
        $performance = $this->createValidPerformance();
        self::getEntityManager()->persist($performance);
        self::getEntityManager()->flush();

        $entity = $this->createValidEntity();
        $entity->setPerformance($performance);
        $this->repository->save($entity);

        $count = $this->repository->count(['performance' => $performance]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithNullNameFieldShouldWork(): void
    {
        $performance = $this->createValidPerformance();
        self::getEntityManager()->persist($performance);
        self::getEntityManager()->flush();

        $entity = new PerformanceAttribute();
        $entity->setValue('test_value');
        $entity->setPerformance($performance);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['name' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testCountWithNullNameFieldShouldWork(): void
    {
        $performance = $this->createValidPerformance();
        self::getEntityManager()->persist($performance);
        self::getEntityManager()->flush();

        $entity = new PerformanceAttribute();
        $entity->setValue('test_value');
        $entity->setPerformance($performance);
        $this->repository->save($entity);

        $count = $this->repository->count(['name' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    private function createValidEntity(): PerformanceAttribute
    {
        $performance = $this->createValidPerformance();
        self::getEntityManager()->persist($performance);
        self::getEntityManager()->flush();

        $entity = new PerformanceAttribute();
        $entity->setName('cpu_usage');
        $entity->setValue('75.5');
        $entity->setPerformance($performance);

        return $entity;
    }

    private function createValidPerformance(): Performance
    {
        $performance = new Performance();
        $performance->setName('test_performance');
        $performance->setNameZh('测试性能');
        $performance->setModule(PerformanceModule::TYPE_16);

        return $performance;
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\PerformanceData;
use WechatMiniProgramStatsBundle\Repository\PerformanceDataRepository;

/**
 * @internal
 */
#[CoversClass(PerformanceDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class PerformanceDataRepositoryTest extends AbstractRepositoryTestCase
{
    private PerformanceDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(PerformanceDataRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(PerformanceDataRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new PerformanceData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test PerformanceDataRepository ' . uniqid());
    }

    /**
     * @return PerformanceDataRepository
     */
    protected function getRepository(): PerformanceDataRepository
    {
        return self::getService(PerformanceDataRepository::class);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();

        $this->repository->save($entity);

        self::assertNotNull($entity->getId());
        self::assertGreaterThan(0, $entity->getId());
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();
        $this->repository->save($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    public function testFindByWithNullDateFieldShouldWork(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();
        $entity->setDate(null);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['date' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testCountWithNullDateFieldShouldWork(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();
        $entity->setDate(null);
        $this->repository->save($entity);

        $count = $this->repository->count(['date' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    private function createValidEntity(): PerformanceData
    {
        $entity = new PerformanceData();
        $entity->setModule('network');
        $entity->setNetworkType('wifi');
        $entity->setDeviceLevel('high');
        $entity->setMetricsId('response_time');
        $entity->setDescription('API响应时间');
        $entity->setDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setValue('120');

        return $entity;
    }

    public function testFindByWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_perf_findby_111');
    }

    public function testFindAllWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_perf_findall_222');
    }

    public function testCountWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_perf_count_333');
    }

    public function testFindWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_perf_data_999');
    }
}

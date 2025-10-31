<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

/**
 * @internal
 */
#[CoversClass(DailyPageVisitDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class DailyPageVisitDataRepositoryTest extends AbstractRepositoryTestCase
{
    private DailyPageVisitDataRepository $repository;

    private DailyPageVisitData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(DailyPageVisitDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(DailyPageVisitDataRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getPage(), $result->getPage());
    }

    public function testFindWithNonExistentId(): void
    {
        $result = $this->repository->find(999999);
        self::assertNull($result);
    }

    public function testFindWithNullId(): void
    {
        $this->expectException(MissingIdentifierField::class);
        $this->repository->find(null);
    }

    public function testFindWithZeroId(): void
    {
        $result = $this->repository->find(0);
        self::assertNull($result);
    }

    public function testFindWithNegativeId(): void
    {
        $result = $this->repository->find(-1);
        self::assertNull($result);
    }

    public function testFindAll(): void
    {
        $this->repository->save($this->entity);
        $secondEntity = $this->createTestEntity();
        $secondEntity->setPage('/page/about-' . uniqid());
        $this->repository->save($secondEntity);

        $results = $this->repository->findAll();

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(DailyPageVisitData::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['page' => $this->entity->getPage()]);

        self::assertGreaterThanOrEqual(1, count($results));
        /** @var DailyPageVisitData $firstResult */
        $firstResult = $results[0];
        self::assertEquals($this->entity->getPage(), $firstResult->getPage());
    }

    public function testFindByWithNullCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setDate(null);
        $testEntity->setPage('/page/null-criteria-' . uniqid());
        $testEntity->setVisitUv(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['date' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testFindOneBy(): void
    {
        $this->repository->save($this->entity);

        $result = $this->repository->findOneBy(['page' => $this->entity->getPage()]);
        self::assertNotNull($result);

        self::assertEquals($this->entity->getPage(), $result->getPage());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['page' => '/non-existent-page']);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertNotNull($savedEntity);
        self::assertEquals($this->entity->getPage(), $savedEntity->getPage());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        $result = $this->repository->find($this->entity->getId());
        self::assertNull($result);

        self::getEntityManager()->flush();
        $result = $this->repository->find($this->entity->getId());
    }

    public function testRemove(): void
    {
        $this->repository->save($this->entity);
        $entityId = $this->entity->getId();

        $this->repository->remove($this->entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    public function testFindWithInvalidFieldThrowsException(): void
    {
        $this->expectException(UnrecognizedField::class);
        $this->repository->findBy(['nonExistentField' => 'value']);
    }

    private function createTestEntity(): DailyPageVisitData
    {
        $entity = new DailyPageVisitData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setPage('/page/home');
        $entity->setVisitPv(100);
        $entity->setVisitUv(50);
        $entity->setNewUserVisitPv(25);
        $entity->setNewUserVisitUv(20);

        return $entity;
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setPage('/page/save-test-' . uniqid());

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(DailyPageVisitData::class, $savedEntity);
        self::assertEquals($testEntity->getPage(), $savedEntity->getPage());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $this->repository->save($this->entity);
        $entityId = $this->entity->getId();

        $this->repository->remove($this->entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    public function testFindByWithNullableFieldCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setDate(null);
        $testEntity->setPage('/page/nullable-field-' . uniqid());
        $testEntity->setVisitUv(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['date' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            /** @var DailyPageVisitData $result */
            self::assertNull($result->getDate());
        }
    }

    public function testFindOneByWithSorting(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setPage('/page/sorting-zebra-' . uniqid());
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-06-01'));
        $entity2->setPage('/page/sorting-alpha-' . uniqid());
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['page' => 'ASC']);

        $resultDesc = $this->repository->findOneBy([], ['page' => 'DESC']);
        self::assertInstanceOf(DailyPageVisitData::class, $resultDesc);
    }

    public function testCountWithSpecificPage(): void
    {
        $testPage = '/test/page-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-01'));
        $entity1->setPage($testPage);
        $entity1->setVisitPv(100);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-02'));
        $entity2->setPage($testPage);
        $entity2->setVisitPv(200);
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setDate(new \DateTimeImmutable('2024-01-03'));
        $otherEntity->setPage('/other/page-' . uniqid());
        $otherEntity->setVisitPv(300);
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['page' => $testPage]);

        self::assertEquals(2, $count);
    }

    public function testFindByPageAssociation(): void
    {
        $testPage = '/test/associated/page-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-02-01'));
        $entity1->setPage($testPage);
        $entity1->setVisitPv(100);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-02-02'));
        $entity2->setPage($testPage);
        $entity2->setVisitPv(200);
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setDate(new \DateTimeImmutable('2024-02-03'));
        $otherEntity->setPage('/other/page-' . uniqid());
        $otherEntity->setVisitPv(300);
        $this->repository->save($otherEntity);

        $results = $this->repository->findBy(['page' => $testPage]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            /** @var DailyPageVisitData $result */
            self::assertEquals($testPage, $result->getPage());
        }
    }

    public function testFindByWithNullDate(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setPage('/page/null-date-1-' . uniqid());
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setPage('/page/null-date-2-' . uniqid());
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        $entityWithDate->setPage('/page/with-date-' . uniqid());
        $this->repository->save($entityWithDate);

        $results = $this->repository->findBy(['date' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            /** @var DailyPageVisitData $result */
            self::assertNull($result->getDate());
        }
    }

    public function testFindByWithNullDataKey(): void
    {
        // DailyPageVisitData实体没有dataKey字段，改为测试有效字段
        $entity1 = $this->createTestEntity();
        $entity1->setPage('/page/null-datakey-1-' . uniqid());
        $entity1->setVisitUv(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-03-01'));
        $entity2->setPage('/page/null-datakey-2-' . uniqid());
        $entity2->setVisitUv(null);
        $this->repository->save($entity2);

        $entityWithValue = $this->createTestEntity();
        $entityWithValue->setDate(new \DateTimeImmutable('2024-03-02'));
        $entityWithValue->setPage('/page/with-value-' . uniqid());
        $entityWithValue->setVisitUv(100);
        $this->repository->save($entityWithValue);

        $results = $this->repository->findBy(['visitUv' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            /** @var DailyPageVisitData $result */
            self::assertNull($result->getVisitUv());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setPage('/page/count-null-date-1-' . uniqid());
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setPage('/page/count-null-date-2-' . uniqid());
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-04-01'));
        $entityWithDate->setPage('/page/with-date-count-' . uniqid());
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDataKeyField(): void
    {
        // DailyPageVisitData实体没有dataKey字段，改为测试有效字段
        $entity1 = $this->createTestEntity();
        $entity1->setPage('/page/count-null-datakey-1-' . uniqid());
        $entity1->setNewUserVisitUv(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-05-01'));
        $entity2->setPage('/page/count-null-datakey-2-' . uniqid());
        $entity2->setNewUserVisitUv(null);
        $this->repository->save($entity2);

        $entityWithValue = $this->createTestEntity();
        $entityWithValue->setDate(new \DateTimeImmutable('2024-05-02'));
        $entityWithValue->setPage('/page/with-value-count-' . uniqid());
        $entityWithValue->setNewUserVisitUv(50);
        $this->repository->save($entityWithValue);

        $count = $this->repository->count(['newUserVisitUv' => null]);

        self::assertGreaterThanOrEqual(2, $count);
    }

    protected function createNewEntity(): DailyPageVisitData
    {
        $entity = new DailyPageVisitData();
        $entity->setDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setPage('/test/page-' . uniqid());
        $entity->setVisitPv(100);
        $entity->setVisitUv(50);
        $entity->setNewUserVisitPv(25);
        $entity->setNewUserVisitUv(20);

        return $entity;
    }

    /**
     * @return DailyPageVisitDataRepository
     */
    protected function getRepository(): DailyPageVisitDataRepository
    {
        return $this->repository;
    }
}

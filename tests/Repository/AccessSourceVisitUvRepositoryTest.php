<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;
use WechatMiniProgramStatsBundle\Repository\AccessSourceVisitUvRepository;

/**
 * @internal
 */
#[CoversClass(AccessSourceVisitUvRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccessSourceVisitUvRepositoryTest extends AbstractRepositoryTestCase
{
    private AccessSourceVisitUvRepository $repository;

    private AccessSourceVisitUv $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccessSourceVisitUvRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(AccessSourceVisitUvRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getDataKey(), $result->getDataKey());
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
        $secondEntity->setDataKey('test-key-2');
        $this->repository->save($secondEntity);

        $results = $this->repository->findAll();

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(AccessSourceVisitUv::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['dataKey' => $this->entity->getDataKey()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($this->entity->getDataKey(), $results[0]->getDataKey());
    }

    public function testFindByWithNullCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setAccount(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testFindOneBy(): void
    {
        $this->repository->save($this->entity);

        $result = $this->repository->findOneBy(['dataKey' => $this->entity->getDataKey()]);
        self::assertNotNull($result);

        self::assertEquals($this->entity->getDataKey(), $result->getDataKey());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['dataKey' => 'non-existent-key']);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertNotNull($savedEntity);
        self::assertEquals($this->entity->getDataKey(), $savedEntity->getDataKey());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        // Entity should have ID after persist
        self::assertNotNull($this->entity->getId());

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

    private function createTestEntity(): AccessSourceVisitUv
    {
        $entity = new AccessSourceVisitUv();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setDataKey('test-key');
        $entity->setDataValue('test-value');

        return $entity;
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setDataKey('save-test-key');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertNotNull($savedEntity);
        self::assertEquals('save-test-key', $savedEntity->getDataKey());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $this->repository->save($this->entity);
        $entityId = $this->entity->getId();

        $this->repository->remove($this->entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    private function createAccount(): Account
    {
        $account = new Account();
        $account->setName('test-account-name');
        $account->setAppId('test-app-id-' . time());
        $account->setAppSecret('test-app-secret');
        $entityManager = self::getEntityManager();
        $entityManager->persist($account);
        $entityManager->flush();

        return $account;
    }

    public function testFindOneByWithSorting(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDataKey('zebra-key');
        $entity1->setDataValue('value-1');
        $this->repository->save($entity1, false);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey('alpha-key');
        $entity2->setDataValue('value-2');
        $this->repository->save($entity2, false);

        self::getEntityManager()->flush();

        // 使用具体的条件来限制查询范围，避免其他测试数据干扰
        $results = $this->repository->findBy(['dataValue' => ['value-1', 'value-2']], ['dataKey' => 'ASC']);

        self::assertNotEmpty($results);
        self::assertEquals('alpha-key', $results[0]->getDataKey());

        $resultsDesc = $this->repository->findBy(['dataValue' => ['value-1', 'value-2']], ['dataKey' => 'DESC']);
        self::assertNotEmpty($resultsDesc);
        self::assertEquals('zebra-key', $resultsDesc[0]->getDataKey());
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDataKey('test-count-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDataKey('test-count-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setDataKey('test-no-account');
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDataKey('test-find-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDataKey('test-find-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setDataKey('test-no-account-association');
        $this->repository->save($otherEntity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals($account->getId(), $result->getAccount()?->getId());
        }
    }

    public function testFindByWithNullDate(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setDataKey('test-null-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setDataKey('test-null-date-2');
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        $entityWithDate->setDataKey('test-with-date');
        $this->repository->save($entityWithDate);

        $results = $this->repository->findBy(['date' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testFindByWithNullDataKey(): void
    {
        $testId = 'null-datakey-test-' . time();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue('test-null-datakey-value-1-' . $testId);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue('test-null-datakey-value-2-' . $testId);
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey');
        $entityWithDataKey->setDataValue('test-value-' . $testId);
        $this->repository->save($entityWithDataKey);

        // 使用具体的 dataValue 条件来确保只查询我们创建的测试数据
        $results = $this->repository->findBy([
            'dataKey' => null,
            'dataValue' => ['test-null-datakey-value-1-' . $testId, 'test-null-datakey-value-2-' . $testId],
        ]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDataKey());
        }
    }

    public function testFindByWithNullDataValue(): void
    {
        $testId = 'null-datavalue-test-' . time();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey('test-null-datavalue-key-1-' . $testId);
        $entity1->setDataValue(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey('test-null-datavalue-key-2-' . $testId);
        $entity2->setDataValue(null);
        $this->repository->save($entity2);

        $entityWithDataValue = $this->createTestEntity();
        $entityWithDataValue->setDataKey('test-with-datavalue-key-' . $testId);
        $entityWithDataValue->setDataValue('test-value');
        $this->repository->save($entityWithDataValue);

        // 使用具体的 dataKey 条件来确保只查询我们创建的测试数据
        $results = $this->repository->findBy([
            'dataValue' => null,
            'dataKey' => ['test-null-datavalue-key-1-' . $testId, 'test-null-datavalue-key-2-' . $testId],
        ]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDataValue());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setDataKey('test-count-null-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setDataKey('test-count-null-date-2');
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        $entityWithDate->setDataKey('test-with-date-count');
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDataKeyField(): void
    {
        $testId = 'count-null-datakey-test-' . time();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue('test-count-null-datakey-value-1-' . $testId);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue('test-count-null-datakey-value-2-' . $testId);
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey-count');
        $entityWithDataKey->setDataValue('test-value-' . $testId);
        $this->repository->save($entityWithDataKey);

        // 计算具有 null dataKey 且 dataValue 匹配我们测试数据的记录数
        $specificNullDataKeyEntities = $this->repository->findBy([
            'dataKey' => null,
            'dataValue' => ['test-count-null-datakey-value-1-' . $testId, 'test-count-null-datakey-value-2-' . $testId],
        ]);

        self::assertCount(2, $specificNullDataKeyEntities);
    }

    public function testCountWithNullDataValueField(): void
    {
        $testId = 'count-null-datavalue-test-' . time();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey('test-count-null-datavalue-key-1-' . $testId);
        $entity1->setDataValue(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey('test-count-null-datavalue-key-2-' . $testId);
        $entity2->setDataValue(null);
        $this->repository->save($entity2);

        $entityWithDataValue = $this->createTestEntity();
        $entityWithDataValue->setDataKey('test-with-datavalue-key-count-' . $testId);
        $entityWithDataValue->setDataValue('test-value');
        $this->repository->save($entityWithDataValue);

        // 计算具有 null dataValue 且 dataKey 匹配我们测试数据的记录数
        $specificNullDataValueEntities = $this->repository->findBy([
            'dataValue' => null,
            'dataKey' => ['test-count-null-datavalue-key-1-' . $testId, 'test-count-null-datavalue-key-2-' . $testId],
        ]);

        self::assertCount(2, $specificNullDataValueEntities);
    }

    protected function createNewEntity(): object
    {
        return new AccessSourceVisitUv();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test AccessSourceVisitUvRepository ' . uniqid());
    }

    /**
     * @return AccessSourceVisitUvRepository
     */
    protected function getRepository(): AccessSourceVisitUvRepository
    {
        return $this->repository;
    }
}

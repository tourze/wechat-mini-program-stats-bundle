<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;
use WechatMiniProgramStatsBundle\Repository\AccessStayTimeInfoDataRepository;

/**
 * @internal
 */
#[CoversClass(AccessStayTimeInfoDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccessStayTimeInfoDataRepositoryTest extends AbstractRepositoryTestCase
{
    private AccessStayTimeInfoDataRepository $repository;

    private AccessStayTimeInfoData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccessStayTimeInfoDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(AccessStayTimeInfoDataRepository::class));
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
        self::assertContainsOnlyInstancesOf(AccessStayTimeInfoData::class, $results);
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

        // 对于使用 Snowflake ID 生成器的实体，ID 会在 save 时立即生成
        // 但我们可以检查其他属性是否正确保存
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);
        self::assertEquals($this->entity->getDataKey(), $result->getDataKey());

        // 再次 flush 确保没有问题
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

    private function createTestEntity(): AccessStayTimeInfoData
    {
        $entity = new AccessStayTimeInfoData();
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
        self::assertInstanceOf(AccessStayTimeInfoData::class, $savedEntity);
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

    public function testFindByWithNullableFieldCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setAccount(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getAccount());
        }
    }

    public function testFindOneByWithOrderBy(): void
    {
        $uniquePrefix = 'test-order-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey($uniquePrefix . '-zebra-key');
        $entity1->setDataValue('value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey($uniquePrefix . '-alpha-key');
        $entity2->setDataValue('value-2');
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $result = $this->repository->findOneBy(
            ['dataKey' => [$uniquePrefix . '-zebra-key', $uniquePrefix . '-alpha-key']],
            ['dataKey' => 'ASC']
        );

        self::assertNotNull($result);
        self::assertEquals($uniquePrefix . '-alpha-key', $result->getDataKey());
    }

    public function testFindByAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity = $this->createTestEntity();
        $entity->setAccount($account);
        $entity->setDataKey('test-with-account');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($account->getId(), $results[0]->getAccount()?->getId());
    }

    public function testFindByNullDateField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setDate(null);
        $entity->setDataKey('test-null-date');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['date' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testFindByNullDataKeyField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setDataKey(null);
        $entity->setDataValue('test-null-datakey');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['dataKey' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getDataKey());
        }
    }

    public function testFindByNullDataValueField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setDataKey('test-null-datavalue');
        $entity->setDataValue(null);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['dataValue' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getDataValue());
        }
    }

    public function testCountWithAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDataKey('test-count-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDataKey('test-count-account-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDate(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setDataKey('test-count-null-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setDataKey('test-count-null-date-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDataKey(): void
    {
        $uniqueMarker = 'test-count-null-datakey-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue($uniqueMarker . '-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue($uniqueMarker . '-2');
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $count = $this->repository->count([
            'dataKey' => null,
            'dataValue' => [$uniqueMarker . '-1', $uniqueMarker . '-2'],
        ]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDataValue(): void
    {
        $uniqueMarker = 'test-count-null-datavalue-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey($uniqueMarker . '-1');
        $entity1->setDataValue(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey($uniqueMarker . '-2');
        $entity2->setDataValue(null);
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $count = $this->repository->count([
            'dataValue' => null,
            'dataKey' => [$uniqueMarker . '-1', $uniqueMarker . '-2'],
        ]);

        self::assertEquals(2, $count);
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
        $uniquePrefix = 'test-sorting-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey($uniquePrefix . '-zebra-key');
        $entity1->setDataValue('value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey($uniquePrefix . '-alpha-key');
        $entity2->setDataValue('value-2');
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $result = $this->repository->findOneBy(
            ['dataKey' => [$uniquePrefix . '-zebra-key', $uniquePrefix . '-alpha-key']],
            ['dataKey' => 'ASC']
        );

        self::assertNotNull($result);
        self::assertEquals($uniquePrefix . '-alpha-key', $result->getDataKey());

        $resultDesc = $this->repository->findOneBy(
            ['dataKey' => [$uniquePrefix . '-zebra-key', $uniquePrefix . '-alpha-key']],
            ['dataKey' => 'DESC']
        );
        self::assertInstanceOf(AccessStayTimeInfoData::class, $resultDesc);
        self::assertEquals($uniquePrefix . '-zebra-key', $resultDesc->getDataKey());
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
        $uniqueMarker = 'test-null-datakey-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue($uniqueMarker . '-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue($uniqueMarker . '-2');
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey');
        $entityWithDataKey->setDataValue('test-value');
        $this->repository->save($entityWithDataKey);

        // 使用具体的 dataValue 条件来确保只查询我们创建的测试数据
        $results = $this->repository->findBy([
            'dataKey' => null,
            'dataValue' => [$uniqueMarker . '-1', $uniqueMarker . '-2'],
        ]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDataKey());
        }
    }

    public function testFindByWithNullDataValue(): void
    {
        $uniqueMarker = 'test-null-datavalue-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey($uniqueMarker . '-1');
        $entity1->setDataValue(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey($uniqueMarker . '-2');
        $entity2->setDataValue(null);
        $this->repository->save($entity2);

        $entityWithDataValue = $this->createTestEntity();
        $entityWithDataValue->setDataKey('test-with-datavalue-key');
        $entityWithDataValue->setDataValue('test-value');
        $this->repository->save($entityWithDataValue);

        // 使用具体的 dataKey 条件来确保只查询我们创建的测试数据
        $results = $this->repository->findBy([
            'dataValue' => null,
            'dataKey' => [$uniqueMarker . '-1', $uniqueMarker . '-2'],
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
        $uniqueMarker = 'test-count-null-datakey-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue($uniqueMarker . '-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue($uniqueMarker . '-2');
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey-count');
        $entityWithDataKey->setDataValue('test-value');
        $this->repository->save($entityWithDataKey);

        // 使用更具体的条件来避免其他测试数据的干扰
        $count = $this->repository->count([
            'dataKey' => null,
            'dataValue' => [$uniqueMarker . '-1', $uniqueMarker . '-2'],
        ]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDataValueField(): void
    {
        $uniqueMarker = 'test-count-null-datavalue-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey($uniqueMarker . '-1');
        $entity1->setDataValue(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey($uniqueMarker . '-2');
        $entity2->setDataValue(null);
        $this->repository->save($entity2);

        $entityWithDataValue = $this->createTestEntity();
        $entityWithDataValue->setDataKey('test-with-datavalue-key-count');
        $entityWithDataValue->setDataValue('test-value');
        $this->repository->save($entityWithDataValue);

        // 使用更具体的条件来避免其他测试数据的干扰
        $count = $this->repository->count([
            'dataValue' => null,
            'dataKey' => [$uniqueMarker . '-1', $uniqueMarker . '-2'],
        ]);

        self::assertEquals(2, $count);
    }

    protected function createNewEntity(): object
    {
        return new AccessStayTimeInfoData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test AccessStayTimeInfoDataRepository ' . uniqid());
    }

    /**
     * @return AccessStayTimeInfoDataRepository
     */
    protected function getRepository(): AccessStayTimeInfoDataRepository
    {
        return $this->repository;
    }
}

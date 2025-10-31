<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;
use WechatMiniProgramStatsBundle\Repository\AccessSourceSessionCntRepository;

/**
 * @internal
 */
#[CoversClass(AccessSourceSessionCntRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccessSourceSessionCntRepositoryTest extends AbstractRepositoryTestCase
{
    private AccessSourceSessionCntRepository $repository;

    private AccessSourceSessionCnt $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccessSourceSessionCntRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(AccessSourceSessionCntRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertInstanceOf(AccessSourceSessionCnt::class, $result);

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
        self::assertContainsOnlyInstancesOf(AccessSourceSessionCnt::class, $results);
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
        self::assertInstanceOf(AccessSourceSessionCnt::class, $savedEntity);
        self::assertEquals($this->entity->getDataKey(), $savedEntity->getDataKey());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        // Entity should have an ID after persist but before flush
        self::assertNotNull($this->entity->getId());
        self::assertIsString($this->entity->getId());

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

    private function createTestEntity(): AccessSourceSessionCnt
    {
        $entity = new AccessSourceSessionCnt();
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
        self::assertInstanceOf(AccessSourceSessionCnt::class, $savedEntity);
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

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
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

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account = $this->createAccount();

        $entity = $this->createTestEntity();
        $entity->setAccount($account);
        $entity->setDataKey('test-findone-account');
        $this->repository->save($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        self::assertNotNull($result);
        self::assertEquals($account->getId(), $result->getAccount()?->getId());
        self::assertEquals('test-findone-account', $result->getDataKey());
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
        $uniquePrefix = uniqid('test-');

        $entity1 = $this->createTestEntity();
        $entity1->setDataKey($uniquePrefix . '-zebra-key');
        $entity1->setDataValue('value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey($uniquePrefix . '-alpha-key');
        $entity2->setDataValue('value-2');
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['dataKey' => [$uniquePrefix . '-alpha-key', $uniquePrefix . '-zebra-key']], ['dataKey' => 'ASC']);

        self::assertNotNull($result);
        self::assertEquals($uniquePrefix . '-alpha-key', $result->getDataKey());

        $resultDesc = $this->repository->findOneBy(['dataKey' => [$uniquePrefix . '-alpha-key', $uniquePrefix . '-zebra-key']], ['dataKey' => 'DESC']);
        self::assertInstanceOf(AccessSourceSessionCnt::class, $resultDesc);
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

    public function testFindBySpecificDate(): void
    {
        $testDate = new \DateTimeImmutable('2024-01-02');

        $entity1 = $this->createTestEntity();
        $entity1->setDate($testDate);
        $entity1->setDataKey('test-specific-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate($testDate);
        $entity2->setDataKey('test-specific-date-2');
        $this->repository->save($entity2);

        $entityWithOtherDate = $this->createTestEntity();
        $entityWithOtherDate->setDate(new \DateTimeImmutable('2024-01-03'));
        $entityWithOtherDate->setDataKey('test-other-date');
        $this->repository->save($entityWithOtherDate);

        $results = $this->repository->findBy(['date' => $testDate]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals($testDate, $result->getDate());
        }
    }

    public function testFindByWithEmptyDataKey(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDataKey('');
        $entity1->setDataValue('test-empty-datakey-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey('');
        $entity2->setDataValue('test-empty-datakey-value-2');
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey');
        $entityWithDataKey->setDataValue('test-value');
        $this->repository->save($entityWithDataKey);

        $results = $this->repository->findBy(['dataKey' => '']);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals('', $result->getDataKey());
        }
    }

    public function testCountWithSpecificDate(): void
    {
        $testDate = new \DateTimeImmutable('2024-01-04');

        $entity1 = $this->createTestEntity();
        $entity1->setDate($testDate);
        $entity1->setDataKey('test-count-specific-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate($testDate);
        $entity2->setDataKey('test-count-specific-date-2');
        $this->repository->save($entity2);

        $entityWithOtherDate = $this->createTestEntity();
        $entityWithOtherDate->setDate(new \DateTimeImmutable('2024-01-05'));
        $entityWithOtherDate->setDataKey('test-other-date-count');
        $this->repository->save($entityWithOtherDate);

        $count = $this->repository->count(['date' => $testDate]);

        self::assertEquals(2, $count);
    }

    public function testCountWithEmptyDataKeyField(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDataKey('');
        $entity1->setDataValue('test-count-empty-datakey-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey('');
        $entity2->setDataValue('test-count-empty-datakey-value-2');
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey-count');
        $entityWithDataKey->setDataValue('test-value');
        $this->repository->save($entityWithDataKey);

        $count = $this->repository->count(['dataKey' => '']);

        self::assertEquals(2, $count);
    }

    protected function createNewEntity(): AccessSourceSessionCnt
    {
        $entity = new AccessSourceSessionCnt();
        $entity->setDate(new \DateTimeImmutable('2024-01-' . rand(1, 28)));
        $entity->setDataKey('test-key-' . uniqid());
        $entity->setDataValue('test-value-' . uniqid());

        return $entity;
    }

    /**
     * @return AccessSourceSessionCntRepository
     */
    protected function getRepository(): AccessSourceSessionCntRepository
    {
        return $this->repository;
    }
}

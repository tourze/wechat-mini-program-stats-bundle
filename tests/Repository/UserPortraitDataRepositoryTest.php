<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDataRepository;

/**
 * @internal
 */
#[CoversClass(UserPortraitDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserPortraitDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserPortraitDataRepository $repository;

    private UserPortraitData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserPortraitDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserPortraitDataRepository::class));
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setName('save-test-name');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(UserPortraitData::class, $savedEntity);
        self::assertEquals('save-test-name', $savedEntity->getName());
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

    private function createTestEntity(): UserPortraitData
    {
        $entity = new UserPortraitData();
        $entity->setDate('2024-01-15-' . uniqid());
        $entity->setType('test-type-' . uniqid());
        $entity->setName('test-name-' . uniqid());
        $entity->setValue('test-value');
        $entity->setProvince('test-province');
        $entity->setUserType('test-user-type');
        $entity->setBeginTime(new \DateTimeImmutable('2024-01-15 10:00:00'));
        $entity->setEndTime(new \DateTimeImmutable('2024-01-15 18:00:00'));

        return $entity;
    }

    public function testFindOneByWithOrderBy(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setName('zebra-name');
        $entity1->setValue('value-1');
        $this->repository->save($entity1, true);

        $entity2 = $this->createTestEntity();
        $entity2->setName('alpha-name');
        $entity2->setValue('value-2');
        $this->repository->save($entity2, true);

        self::getEntityManager()->clear();

        $result = $this->repository->findOneBy([], ['name' => 'ASC']);

        self::assertNotNull($result);
        self::assertEquals('alpha-name', $result->getName());
    }

    public function testFindByAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity = $this->createTestEntity();
        $entity->setAccount($account);
        $entity->setName('test-with-account');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($account->getId(), $results[0]->getAccount()?->getId());
    }

    public function testFindByNullDateField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setDate(null);
        $entity->setName('test-null-date');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['date' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testFindByNullTypeField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setType(null);
        $entity->setName('test-null-type');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['type' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getType());
        }
    }

    public function testFindByNullBeginTimeField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setBeginTime(null);
        $entity->setName('test-null-begintime');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['beginTime' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getBeginTime());
        }
    }

    public function testFindByNullEndTimeField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setEndTime(null);
        $entity->setName('test-null-endtime');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['endTime' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getEndTime());
        }
    }

    public function testCountWithAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setName('test-count-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setName('test-count-account-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDate(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setName('test-count-null-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setName('test-count-null-date-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullType(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setType(null);
        $entity1->setName('test-count-null-type-1');
        $this->repository->save($entity1, true);

        $entity2 = $this->createTestEntity();
        $entity2->setType(null);
        $entity2->setName('test-count-null-type-2');
        $this->repository->save($entity2, true);

        self::getEntityManager()->clear();

        $count = $this->repository->count(['type' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullBeginTime(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setBeginTime(null);
        $entity1->setName('test-count-null-begintime-1');
        $this->repository->save($entity1, true);

        $entity2 = $this->createTestEntity();
        $entity2->setBeginTime(null);
        $entity2->setName('test-count-null-begintime-2');
        $this->repository->save($entity2, true);

        self::getEntityManager()->clear();

        $count = $this->repository->count(['beginTime' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullEndTime(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setEndTime(null);
        $entity1->setName('test-count-null-endtime-1');
        $this->repository->save($entity1, true);

        $entity2 = $this->createTestEntity();
        $entity2->setEndTime(null);
        $entity2->setName('test-count-null-endtime-2');
        $this->repository->save($entity2, true);

        self::getEntityManager()->clear();

        $count = $this->repository->count(['endTime' => null]);

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
        $entity1 = $this->createTestEntity();
        $entity1->setName('zebra-name');
        $entity1->setValue('value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setName('alpha-name');
        $entity2->setValue('value-2');
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['name' => 'ASC']);

        $resultDesc = $this->repository->findOneBy([], ['name' => 'DESC']);
        self::assertInstanceOf(UserPortraitData::class, $resultDesc);
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-count-account-association-1');
        }
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey('test-count-account-association-2');
        }
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        if (method_exists($otherEntity, 'setDataKey')) {
            $otherEntity->setDataKey('test-no-account');
        }
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-find-account-association-1');
        }
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey('test-find-account-association-2');
        }
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        if (method_exists($otherEntity, 'setDataKey')) {
            $otherEntity->setDataKey('test-no-account-association');
        }
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
        $entity1->setName('test-null-date-name-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setName('test-null-date-name-2');
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate('2024-01-01');
        $entityWithDate->setName('test-with-date-name');
        $this->repository->save($entityWithDate);

        $results = $this->repository->findBy(['date' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testFindByWithNullName(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setName(null);
        $entity1->setValue('test-null-name-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setName(null);
        $entity2->setValue('test-null-name-value-2');
        $this->repository->save($entity2);

        $entityWithName = $this->createTestEntity();
        $entityWithName->setName('test-with-name');
        $entityWithName->setValue('test-value');
        $this->repository->save($entityWithName);

        $results = $this->repository->findBy(['name' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getName());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-count-null-date-1');
        }
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey('test-count-null-date-2');
        }
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate('2024-01-01');
        if (method_exists($entityWithDate, 'setDataKey')) {
            $entityWithDate->setDataKey('test-with-date-count');
        }
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullNameField(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setName(null);
        $entity1->setValue('test-count-null-name-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setName(null);
        $entity2->setValue('test-count-null-name-value-2');
        $this->repository->save($entity2);

        $entityWithName = $this->createTestEntity();
        $entityWithName->setName('test-with-name-count');
        $entityWithName->setValue('test-value');
        $this->repository->save($entityWithName);

        $count = $this->repository->count(['name' => null]);

        self::assertEquals(2, $count);
    }

    protected function createNewEntity(): object
    {
        return new UserPortraitData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test UserPortraitDataRepository ' . uniqid());
    }

    /**
     * @return UserPortraitDataRepository
     */
    protected function getRepository(): UserPortraitDataRepository
    {
        return $this->repository;
    }
}

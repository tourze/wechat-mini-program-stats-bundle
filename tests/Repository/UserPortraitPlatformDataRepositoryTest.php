<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitPlatformDataRepository;

/**
 * @internal
 */
#[CoversClass(UserPortraitPlatformDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserPortraitPlatformDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserPortraitPlatformDataRepository $repository;

    private UserPortraitPlatformData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserPortraitPlatformDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserPortraitPlatformDataRepository::class));
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setName('save-test-name');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(UserPortraitPlatformData::class, $savedEntity);
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

    private function createTestEntity(): UserPortraitPlatformData
    {
        $entity = new UserPortraitPlatformData();
        $entity->setDate('2024-01-15-' . uniqid());
        $entity->setType('test-type-' . uniqid());
        $entity->setName('test-name-' . uniqid());
        $entity->setValue('test-value');
        $entity->setValueId('test-value-id');

        return $entity;
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
        self::assertInstanceOf(UserPortraitPlatformData::class, $resultDesc);
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
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_platform_data'
        );

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
            'DELETE FROM wechat_user_access_portrait_platform_data'
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
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_platform_data'
        );

        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setName('test-count-null-date-name-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setName('test-count-null-date-name-2');
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate('2024-01-01');
        $entityWithDate->setName('test-with-date-count-name');
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullNameField(): void
    {
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_user_access_portrait_platform_data'
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

    private function createAccount(): Account
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret_' . uniqid());
        $account->setValid(true);

        $entityManager = self::getEntityManager();
        $entityManager->persist($account);
        $entityManager->flush();

        return $account;
    }

    protected function createNewEntity(): object
    {
        return new UserPortraitPlatformData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test UserPortraitPlatformDataRepository ' . uniqid());
    }

    /**
     * @return UserPortraitPlatformDataRepository
     */
    protected function getRepository(): UserPortraitPlatformDataRepository
    {
        return $this->repository;
    }
}

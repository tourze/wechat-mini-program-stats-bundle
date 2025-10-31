<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDeviceDataRepository;

/**
 * @internal
 */
#[CoversClass(UserPortraitDeviceDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserPortraitDeviceDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserPortraitDeviceDataRepository $repository;

    private UserPortraitDeviceData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserPortraitDeviceDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserPortraitDeviceDataRepository::class));
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setName('save-test-name');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(UserPortraitDeviceData::class, $savedEntity);
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

    private function createTestEntity(): UserPortraitDeviceData
    {
        $uniqueId = uniqid();
        $entity = new UserPortraitDeviceData();
        $entity->setDate('2024-01-15-' . $uniqueId);
        $entity->setType('test-type-' . $uniqueId);
        $entity->setName('test-name-' . $uniqueId);
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
        self::assertInstanceOf(UserPortraitDeviceData::class, $resultDesc);
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setName('test-count-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setName('test-count-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setName('test-no-account');
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setName('test-find-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setName('test-find-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setName('test-no-account-association');
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
        $entity1->setName('test-null-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setName('test-null-date-2');
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate('2024-01-01');
        $entityWithDate->setName('test-with-date');
        $this->repository->save($entityWithDate);

        $results = $this->repository->findBy(['date' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testFindByWithNullValue(): void
    {
        $uniqueType = 'null-value-test-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setValue(null);
        $entity1->setType($uniqueType);
        $entity1->setName('test-null-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setValue(null);
        $entity2->setType($uniqueType);
        $entity2->setName('test-null-value-2');
        $this->repository->save($entity2);

        $entityWithValue = $this->createTestEntity();
        $entityWithValue->setValue('test-with-value');
        $entityWithValue->setType($uniqueType);
        $entityWithValue->setName('test-with-value-name');
        $this->repository->save($entityWithValue);

        $results = $this->repository->findBy(['value' => null, 'type' => $uniqueType]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getValue());
            self::assertEquals($uniqueType, $result->getType());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setName('test-count-null-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setName('test-count-null-date-2');
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate('2024-01-01');
        $entityWithDate->setName('test-with-date-count');
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullValueField(): void
    {
        $uniqueType = 'count-null-value-test-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setValue(null);
        $entity1->setType($uniqueType);
        $entity1->setName('test-count-null-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setValue(null);
        $entity2->setType($uniqueType);
        $entity2->setName('test-count-null-value-2');
        $this->repository->save($entity2);

        $entityWithValue = $this->createTestEntity();
        $entityWithValue->setValue('test-with-value-count');
        $entityWithValue->setType($uniqueType);
        $entityWithValue->setName('test-with-value-count-name');
        $this->repository->save($entityWithValue);

        $count = $this->repository->count(['value' => null, 'type' => $uniqueType]);

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
        $uniqueId = uniqid('test_entity_', true);
        $entity = new UserPortraitDeviceData();
        $entity->setDate('2024-01-15-' . $uniqueId);
        $entity->setType('test-type-' . $uniqueId);
        $entity->setName('Test UserPortraitDeviceDataRepository ' . $uniqueId);
        $entity->setValue('test-value-' . $uniqueId);
        $entity->setValueId('test-value-id-' . $uniqueId);

        return $entity;
    }

    /**
     * @return UserPortraitDeviceDataRepository
     */
    protected function getRepository(): UserPortraitDeviceDataRepository
    {
        return $this->repository;
    }

    public function testFindByWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();
        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            $connection->close();
            $params = $connection->getParams();
            $dbPath = isset($params['path']) ? trim($params['path'], 'file:') : null;

            if (null === $dbPath) {
                return; // 无法获取数据库路径，跳过此步骤
            }
            if (file_exists($dbPath)) {
                unlink($dbPath);
                $corruptedContent = str_repeat("\x00\xFF\xDE\xAD\xBE\xEF", 2000);
                file_put_contents($dbPath, $corruptedContent, LOCK_EX);
            }
        } else {
            $connection->close();
        }
        // 简化版本：直接关闭连接而不使用反射

        $connection->close();
        $this->repository->findBy([]);
    }

    public function testFindAllWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();
        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            $connection->close();
            $params = $connection->getParams();
            $dbPath = isset($params['path']) ? trim($params['path'], 'file:') : null;

            if (null === $dbPath) {
                return; // 无法获取数据库路径，跳过此步骤
            }
            if (file_exists($dbPath)) {
                unlink($dbPath);
                $corruptedContent = str_repeat("\x00\xFF\xDE\xAD\xBE\xEF", 2000);
                file_put_contents($dbPath, $corruptedContent, LOCK_EX);
            }
        } else {
            $connection->close();
        }
        // 简化版本：直接关闭连接而不使用反射

        $connection->close();
        $this->repository->findAll();
    }

    public function testCountWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();
        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            $connection->close();
            $params = $connection->getParams();
            $dbPath = isset($params['path']) ? trim($params['path'], 'file:') : null;

            if (null === $dbPath) {
                return; // 无法获取数据库路径，跳过此步骤
            }
            if (file_exists($dbPath)) {
                unlink($dbPath);
                $corruptedContent = str_repeat("\x00\xFF\xDE\xAD\xBE\xEF", 2000);
                file_put_contents($dbPath, $corruptedContent, LOCK_EX);
            }
        } else {
            $connection->close();
        }
        // 简化版本：直接关闭连接而不使用反射

        $connection->close();
        $this->repository->count();
    }

    public function testFindWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();
        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            $connection->close();
            $params = $connection->getParams();
            $dbPath = isset($params['path']) ? trim($params['path'], 'file:') : null;

            if (null === $dbPath) {
                return; // 无法获取数据库路径，跳过此步骤
            }
            if (file_exists($dbPath)) {
                unlink($dbPath);
                $corruptedContent = str_repeat("\x00\xFF\xDE\xAD\xBE\xEF", 2000);
                file_put_contents($dbPath, $corruptedContent, LOCK_EX);
            }
        } else {
            $connection->close();
        }
        // 简化版本：直接关闭连接而不使用反射

        $connection->close();
        $this->repository->find(1);
    }
}

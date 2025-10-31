<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;
use WechatMiniProgramStatsBundle\Repository\AccessDepthInfoDataRepository;

/**
 * @internal
 */
#[CoversClass(AccessDepthInfoDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccessDepthInfoDataRepositoryTest extends AbstractRepositoryTestCase
{
    private AccessDepthInfoDataRepository $repository;

    private AccessDepthInfoData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccessDepthInfoDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(AccessDepthInfoDataRepository::class));
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
        self::assertContainsOnlyInstancesOf(AccessDepthInfoData::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['dataKey' => $this->entity->getDataKey()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertInstanceOf(AccessDepthInfoData::class, $results[0]);
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
        self::assertInstanceOf(AccessDepthInfoData::class, $savedEntity);
        self::assertEquals($this->entity->getDataKey(), $savedEntity->getDataKey());
    }

    public function testSaveWithoutFlush(): void
    {
        $originalId = $this->entity->getId();
        $this->repository->save($this->entity, false);

        self::assertNotNull($this->entity->getId());
        self::assertNotEquals($originalId, $this->entity->getId());
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

    private function createTestEntity(): AccessDepthInfoData
    {
        $entity = new AccessDepthInfoData();
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
        self::assertInstanceOf(AccessDepthInfoData::class, $savedEntity);
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
        $account1 = $this->createAccount();
        $account2 = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account1);
        $entity1->setDataKey('test-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account2);
        $entity2->setDataKey('test-account-2');
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['account' => $account2]);

        self::assertNotNull($result);
        self::assertEquals($account2->getId(), $result->getAccount()?->getId());
        self::assertEquals('test-account-2', $result->getDataKey());
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
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey('alpha-key');
        $entity2->setDataValue('value-2');
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['dataKey' => 'ASC']);

        self::assertNotNull($result);
        self::assertEquals('alpha-key', $result->getDataKey());

        $resultDesc = $this->repository->findOneBy([], ['dataKey' => 'DESC']);
        self::assertInstanceOf(AccessDepthInfoData::class, $resultDesc);
        self::assertEquals('zebra-key', $resultDesc->getDataKey());
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
        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue('test-null-datakey-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue('test-null-datakey-value-2');
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey');
        $entityWithDataKey->setDataValue('test-value');
        $this->repository->save($entityWithDataKey);

        $results = $this->repository->findBy(['dataKey' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDataKey());
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
        $entity1 = $this->createTestEntity();
        $entity1->setDataKey(null);
        $entity1->setDataValue('test-count-null-datakey-value-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDataKey(null);
        $entity2->setDataValue('test-count-null-datakey-value-2');
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDataKey('test-with-datakey-count');
        $entityWithDataKey->setDataValue('test-value');
        $this->repository->save($entityWithDataKey);

        $count = $this->repository->count(['dataKey' => null]);

        self::assertEquals(2, $count);
    }

    protected function createNewEntity(): object
    {
        return new AccessDepthInfoData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test AccessDepthInfoDataRepository ' . uniqid());
    }

    /**
     * @return AccessDepthInfoDataRepository
     */
    protected function getRepository(): AccessDepthInfoDataRepository
    {
        return $this->repository;
    }

    /**
     * 修复基类中有问题的测试方法，使用更可靠的数据库不可用模拟
     * 由于基类方法是final的，我们创建一个新的测试方法
     */
    public function testFindByWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);

        // 获取连接信息
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            // 对于SQLite，我们采用更强力的破坏方法
            $connection->close();

            $params = $connection->getParams();
            $dbPath = isset($params['path']) ? trim($params['path'], 'file:') : null;

            if (null === $dbPath) {
                return; // 无法获取数据库路径，跳过此步骤
            }

            if (file_exists($dbPath)) {
                // 彻底删除数据库文件
                unlink($dbPath);

                // 创建一个损坏的文件，包含无效的SQLite头
                $corruptedContent = str_repeat("\x00\xFF\xDE\xAD\xBE\xEF", 2000);
                file_put_contents($dbPath, $corruptedContent, LOCK_EX);

                // 跳过权限操作，因为可能在测试清理时出现问题
            }
        } else {
            // 对于其他数据库，简单关闭连接
            $connection->close();
        }

        // 简化数据库破坏测试：直接关闭连接

        $connection->close();

        // 现在执行查询，应该抛出异常
        $this->repository->findBy([]);
    }
}

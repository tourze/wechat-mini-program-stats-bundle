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
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;
use WechatMiniProgramStatsBundle\Repository\DailySummaryDataRepository;

/**
 * @internal
 */
#[CoversClass(DailySummaryDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class DailySummaryDataRepositoryTest extends AbstractRepositoryTestCase
{
    private DailySummaryDataRepository $repository;

    private DailySummaryData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(DailySummaryDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(DailySummaryDataRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getVisitTotal(), $result->getVisitTotal());
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
        $secondEntity->setDate(new \DateTimeImmutable('2024-01-16'));
        $this->repository->save($secondEntity);

        $results = $this->repository->findAll();

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(DailySummaryData::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['visitTotal' => $this->entity->getVisitTotal()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($this->entity->getVisitTotal(), $results[0]->getVisitTotal());
    }

    public function testFindByWithNullCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setAccount(null);
        $testEntity->setDate(new \DateTimeImmutable('2024-01-01'));
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testFindOneBy(): void
    {
        $this->repository->save($this->entity);

        $result = $this->repository->findOneBy(['visitTotal' => $this->entity->getVisitTotal()]);

        self::assertNotNull($result);
        self::assertEquals($this->entity->getVisitTotal(), $result->getVisitTotal());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['visitTotal' => 'non-existent-total']);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertInstanceOf(DailySummaryData::class, $savedEntity);
        self::assertEquals($this->entity->getVisitTotal(), $savedEntity->getVisitTotal());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        // 对于使用 Snowflake ID 生成器的实体，ID 会在 save 时立即生成
        // 但我们可以检查其他属性是否正确保存
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);
        self::assertEquals($this->entity->getDate(), $result->getDate());
        self::assertEquals($this->entity->getVisitTotal(), $result->getVisitTotal());

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

    private function createTestEntity(): DailySummaryData
    {
        $entity = new DailySummaryData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setVisitTotal('1000');
        $entity->setSharePv('100');
        $entity->setShareUv('50');

        return $entity;
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setVisitTotal('8888');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(DailySummaryData::class, $savedEntity);
        self::assertEquals('8888', $savedEntity->getVisitTotal());
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
        $testEntity->setDate(new \DateTimeImmutable('2024-01-01'));
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getAccount());
        }
    }

    public function testFindOneByWithSorting(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-20'));
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('zebra-key');
        }
        if (method_exists($entity1, 'setDataValue')) {
            $entity1->setDataValue('value-1');
        }
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-21'));
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey('alpha-key');
        }
        if (method_exists($entity2, 'setDataValue')) {
            $entity2->setDataValue('value-2');
        }
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['date' => 'ASC']);

        $resultDesc = $this->repository->findOneBy([], ['date' => 'DESC']);
        self::assertInstanceOf(DailySummaryData::class, $resultDesc);
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDate(new \DateTimeImmutable('2024-01-18'));
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-count-account-association-1');
        }
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-19'));
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
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-find-account-association-1');
        }
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
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
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setAccount($account1);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-null-date-1');
        }
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setAccount($account2);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey('test-null-date-2');
        }
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        if (method_exists($entityWithDate, 'setDataKey')) {
            $entityWithDate->setDataKey('test-with-date');
        }
        $this->repository->save($entityWithDate);

        $results = $this->repository->findBy(['date' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testFindByWithNullDataKey(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account1);
        $entity1->setDate(new \DateTimeImmutable('2024-01-22'));
        $entity1->setVisitTotal(null);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey(null);
        }
        if (method_exists($entity1, 'setDataValue')) {
            $entity1->setDataValue('test-null-datakey-value-1');
        }
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account2);
        $entity2->setDate(new \DateTimeImmutable('2024-01-23'));
        $entity2->setVisitTotal(null);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey(null);
        }
        if (method_exists($entity2, 'setDataValue')) {
            $entity2->setDataValue('test-null-datakey-value-2');
        }
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDate(new \DateTimeImmutable('2024-01-24'));
        $entityWithDataKey->setVisitTotal('9999'); // 确保非null
        if (method_exists($entityWithDataKey, 'setDataKey')) {
            $entityWithDataKey->setDataKey('test-with-datakey');
        }
        if (method_exists($entityWithDataKey, 'setDataValue')) {
            $entityWithDataKey->setDataValue('test-value');
        }
        $this->repository->save($entityWithDataKey);

        $results1 = $this->repository->findBy(['visitTotal' => null, 'account' => $account1]);
        $results2 = $this->repository->findBy(['visitTotal' => null, 'account' => $account2]);
        $results = array_merge($results1, $results2);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getVisitTotal());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setAccount($account1);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey('test-count-null-date-1');
        }
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setAccount($account2);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey('test-count-null-date-2');
        }
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        if (method_exists($entityWithDate, 'setDataKey')) {
            $entityWithDate->setDataKey('test-with-date-count');
        }
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDataKeyField(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account1);
        $entity1->setDate(new \DateTimeImmutable('2024-01-25'));
        $entity1->setVisitTotal(null);
        if (method_exists($entity1, 'setDataKey')) {
            $entity1->setDataKey(null);
        }
        if (method_exists($entity1, 'setDataValue')) {
            $entity1->setDataValue('test-count-null-datakey-value-1');
        }
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account2);
        $entity2->setDate(new \DateTimeImmutable('2024-01-26'));
        $entity2->setVisitTotal(null);
        if (method_exists($entity2, 'setDataKey')) {
            $entity2->setDataKey(null);
        }
        if (method_exists($entity2, 'setDataValue')) {
            $entity2->setDataValue('test-count-null-datakey-value-2');
        }
        $this->repository->save($entity2);

        $entityWithDataKey = $this->createTestEntity();
        $entityWithDataKey->setDate(new \DateTimeImmutable('2024-01-27'));
        $entityWithDataKey->setVisitTotal('9999'); // 确保非null
        if (method_exists($entityWithDataKey, 'setDataKey')) {
            $entityWithDataKey->setDataKey('test-with-datakey-count');
        }
        if (method_exists($entityWithDataKey, 'setDataValue')) {
            $entityWithDataKey->setDataValue('test-value');
        }
        $this->repository->save($entityWithDataKey);

        $count1 = $this->repository->count(['visitTotal' => null, 'account' => $account1]);
        $count2 = $this->repository->count(['visitTotal' => null, 'account' => $account2]);
        $totalCount = $count1 + $count2;

        self::assertEquals(2, $totalCount);
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
        $entity = new DailySummaryData();
        $entity->setDate(new \DateTimeImmutable('2024-01-' . (15 + (int) substr($uniqueId, -2) % 10)));
        $entity->setVisitTotal('100-' . $uniqueId);
        $entity->setSharePv('50-' . $uniqueId);
        $entity->setShareUv('25-' . $uniqueId);

        return $entity;
    }

    /**
     * @return DailySummaryDataRepository
     */
    protected function getRepository(): DailySummaryDataRepository
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

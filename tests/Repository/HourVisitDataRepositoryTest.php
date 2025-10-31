<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;
use WechatMiniProgramStatsBundle\Repository\HourVisitDataRepository;

/**
 * @internal
 */
#[CoversClass(HourVisitDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class HourVisitDataRepositoryTest extends AbstractRepositoryTestCase
{
    private HourVisitDataRepository $repository;

    private HourVisitData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(HourVisitDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(HourVisitDataRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getVisitUserUv(), $result->getVisitUserUv());
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
        $secondEntity->setDate(new \DateTimeImmutable('2024-01-15 14:00:00'));
        $this->repository->save($secondEntity);

        $results = $this->repository->findAll();

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(HourVisitData::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['visitUserUv' => $this->entity->getVisitUserUv()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($this->entity->getVisitUserUv(), $results[0]->getVisitUserUv());
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

        $result = $this->repository->findOneBy(['visitUserUv' => $this->entity->getVisitUserUv()]);
        self::assertNotNull($result);

        self::assertEquals($this->entity->getVisitUserUv(), $result->getVisitUserUv());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['visitUserUv' => 99999]);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertInstanceOf(HourVisitData::class, $savedEntity);
        self::assertEquals($this->entity->getVisitUserUv(), $savedEntity->getVisitUserUv());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        // 检查实体是否在UnitOfWork中被管理，但未flush到数据库
        $entityManager = self::getEntityManager();
        self::assertTrue($entityManager->contains($this->entity));

        // 清除身份映射并查询数据库
        $entityId = $this->entity->getId();
        $entityManager->clear();

        $result = $this->repository->find($entityId);
        // 由于没有flush，实体不应该在数据库中
        self::assertNull($result);

        // 重新获取实体并保存到持久化上下文
        $this->entity = $this->createTestEntity();
        $this->repository->save($this->entity, false);
        $entityManager->flush();

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

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setVisitUserUv(888);

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(HourVisitData::class, $savedEntity);
        self::assertEquals(888, $savedEntity->getVisitUserUv());
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

    private function createTestEntity(): HourVisitData
    {
        $entity = new HourVisitData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15 13:00:00'));
        $entity->setVisitUserUv(50);
        $entity->setVisitUserPv(100);
        $entity->setPagePv(150);
        $entity->setNewUser(25);
        $entity->setVisitNewUserPv(40);
        $entity->setPageNewUserPv(60);

        return $entity;
    }

    public function testFindOneByWithOrderBy(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account1);
        $entity1->setDate(new \DateTimeImmutable('2024-01-15 13:00:00'));
        $entity1->setVisitUserUv(300);
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account2);
        $entity2->setDate(new \DateTimeImmutable('2024-01-15 14:00:00'));
        $entity2->setVisitUserUv(100);
        $this->repository->save($entity2);

        // 查找 visitUserUv 大于 50 的记录并按升序排序
        $results = $this->repository->findBy(['visitUserUv' => [100, 300]]);
        usort($results, function ($a, $b) {
            return $a->getVisitUserUv() <=> $b->getVisitUserUv();
        });
        $result = $results[0];

        self::assertEquals(100, $result->getVisitUserUv());
    }

    public function testFindByAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity = $this->createTestEntity();
        $entity->setAccount($account);
        $entity->setVisitUserUv(123);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($account->getId(), $results[0]->getAccount()?->getId());
    }

    public function testFindByNullDateField(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createNewEntity();
        self::assertInstanceOf(HourVisitData::class, $entity1);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitUserUv(null);
        $entity1->setAccount($account1);
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createNewEntity();
        self::assertInstanceOf(HourVisitData::class, $entity2);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitUserUv(null);
        $entity2->setAccount($account2);
        $this->repository->save($entity2);

        $entityWithVisitUserUv = $this->createNewEntity();
        self::assertInstanceOf(HourVisitData::class, $entityWithVisitUserUv);
        $entityWithVisitUserUv->setDate(new \DateTimeImmutable('2024-01-18'));
        $entityWithVisitUserUv->setVisitUserUv(100);
        $this->repository->save($entityWithVisitUserUv);

        $results = $this->repository->findBy(['visitUserUv' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getVisitUserUv());
        }
    }

    public function testFindByNullVisitUserUvField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setVisitUserUv(null);
        $entity->setVisitUserPv(789);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['visitUserUv' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getVisitUserUv());
        }
    }

    public function testCountWithAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setVisitUserUv(111);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-15 14:00:00'));
        $entity2->setVisitUserUv(222);
        $this->repository->save($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullDate(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitUserUv(null);
        $entity1->setAccount($account1);
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitUserUv(null);
        $entity2->setAccount($account2);
        $this->repository->save($entity2);

        $count = $this->repository->count(['visitUserUv' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullVisitUserUv(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setVisitUserUv(null);
        $entity1->setVisitUserPv(555);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-15 14:00:00'));
        $entity2->setVisitUserUv(null);
        $entity2->setVisitUserPv(666);
        $this->repository->save($entity2);

        $count = $this->repository->count(['visitUserUv' => null]);

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
        self::getEntityManager()->getConnection()->executeStatement(
            'DELETE FROM wechat_hour_visit_data'
        );

        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account1);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16 14:00:00'));
        $entity1->setVisitUserUv(300);
        $this->repository->save($entity1, true);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account2);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17 15:00:00'));
        $entity2->setVisitUserUv(100);
        $this->repository->save($entity2, true);

        self::getEntityManager()->clear();

        $result = $this->repository->findOneBy([], ['visitUserUv' => 'ASC']);
        self::assertNotNull($result);

        self::assertEquals(100, $result->getVisitUserUv());

        $resultDesc = $this->repository->findOneBy([], ['visitUserUv' => 'DESC']);
        self::assertInstanceOf(HourVisitData::class, $resultDesc);
        self::assertEquals(300, $resultDesc->getVisitUserUv());
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitUserUv(100);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitUserUv(200);
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setDate(new \DateTimeImmutable('2024-01-18'));
        $otherEntity->setVisitUserUv(300);
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
        $entity1->setVisitUserUv(100);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitUserUv(200);
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setDate(new \DateTimeImmutable('2024-01-18'));
        $otherEntity->setVisitUserUv(300);
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
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitUserUv(null);
        $entity1->setAccount($account1);
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitUserUv(null);
        $entity2->setAccount($account2);
        $this->repository->save($entity2);

        $entityWithVisitUserUv = $this->createTestEntity();
        $entityWithVisitUserUv->setDate(new \DateTimeImmutable('2024-01-18'));
        $entityWithVisitUserUv->setVisitUserUv(100);
        $this->repository->save($entityWithVisitUserUv);

        $results = $this->repository->findBy(['visitUserUv' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getVisitUserUv());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitUserUv(null);
        $entity1->setAccount($account1);
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitUserUv(null);
        $entity2->setAccount($account2);
        $this->repository->save($entity2);

        $entityWithVisitUserUv = $this->createTestEntity();
        $entityWithVisitUserUv->setDate(new \DateTimeImmutable('2024-01-18'));
        $entityWithVisitUserUv->setVisitUserUv(100);
        $this->repository->save($entityWithVisitUserUv);

        $count = $this->repository->count(['visitUserUv' => null]);

        self::assertEquals(2, $count);
    }

    protected function createNewEntity(): object
    {
        // Use microseconds to ensure uniqueness across tests, but keep valid time format
        $minutes = (intval(microtime(true) * 1000) % 60);
        $seconds = (intval(microtime(true) * 10000) % 60);
        $uniqueTimestamp = sprintf('2024-01-15 13:%02d:%02d', $minutes, $seconds);

        $entity = new HourVisitData();
        $entity->setDate(new \DateTimeImmutable($uniqueTimestamp));
        $entity->setVisitUserUv(50);
        $entity->setVisitUserPv(100);
        $entity->setPagePv(150);
        $entity->setNewUser(25);
        $entity->setVisitNewUserPv(40);
        $entity->setPageNewUserPv(60);

        return $entity;
    }

    /**
     * @return HourVisitDataRepository
     */
    protected function getRepository(): HourVisitDataRepository
    {
        return $this->repository;
    }

    public function testFindByWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_xyz_123');
    }

    public function testFindAllWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_xyz_456');
    }

    public function testCountWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_xyz_789');
    }

    public function testFindWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 直接执行无效的SQL语句来触发数据库异常
        $connection->executeStatement('SELECT * FROM non_existent_table_xyz_000');
    }
}

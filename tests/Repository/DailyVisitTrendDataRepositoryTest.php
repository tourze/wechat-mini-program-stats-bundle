<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

/**
 * @internal
 */
#[CoversClass(DailyVisitTrendDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class DailyVisitTrendDataRepositoryTest extends AbstractRepositoryTestCase
{
    private DailyVisitTrendDataRepository $repository;

    private DailyVisitTrendData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(DailyVisitTrendDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(DailyVisitTrendDataRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getSessionCnt(), $result->getSessionCnt());
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
        self::assertContainsOnlyInstancesOf(DailyVisitTrendData::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['sessionCnt' => $this->entity->getSessionCnt()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($this->entity->getSessionCnt(), $results[0]->getSessionCnt());
    }

    public function testFindByWithNullCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setAccount(null);
        $testEntity->setStayTimeUv(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testFindOneBy(): void
    {
        $this->repository->save($this->entity);

        $result = $this->repository->findOneBy(['sessionCnt' => $this->entity->getSessionCnt()]);
        self::assertNotNull($result);

        self::assertEquals($this->entity->getSessionCnt(), $result->getSessionCnt());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['sessionCnt' => 99999]);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertInstanceOf(DailyVisitTrendData::class, $savedEntity);
        self::assertEquals($this->entity->getSessionCnt(), $savedEntity->getSessionCnt());
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
        $testEntity->setSessionCnt(888);

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(DailyVisitTrendData::class, $savedEntity);
        self::assertEquals(888, $savedEntity->getSessionCnt());
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
        $testEntity->setStayTimeUv(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getAccount());
        }
    }

    private function createTestEntity(): DailyVisitTrendData
    {
        $entity = new DailyVisitTrendData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setSessionCnt(50);
        $entity->setVisitPv(100);
        $entity->setVisitUv(75);
        $entity->setVisitUvNew(25);
        $entity->setStayTimeUv('120.5');
        $entity->setStayTimeSession('180.0');
        $entity->setVisitDepth('2.3');

        return $entity;
    }

    public function testFindOneByWithSorting(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['date' => 'ASC']);

        $resultDesc = $this->repository->findOneBy([], ['date' => 'DESC']);
        self::assertInstanceOf(DailyVisitTrendData::class, $resultDesc);
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setAccount($account);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setAccount($account);
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setAccount($account);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setAccount($account);
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
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
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setAccount($account2);
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        $this->repository->save($entityWithDate);

        $results = $this->repository->findBy(['date' => null]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertNull($result->getDate());
        }
    }

    public function testCountWithNullDateField(): void
    {
        $account1 = $this->createAccount();
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $entity1->setAccount($account1);
        $this->repository->save($entity1);

        $account2 = $this->createAccount();
        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $entity2->setAccount($account2);
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

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
        return new DailyVisitTrendData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test DailyVisitTrendDataRepository ' . uniqid());
    }

    /**
     * @return DailyVisitTrendDataRepository
     */
    protected function getRepository(): DailyVisitTrendDataRepository
    {
        return $this->repository;
    }
}

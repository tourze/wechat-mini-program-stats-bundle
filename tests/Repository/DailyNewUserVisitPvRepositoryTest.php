<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

/**
 * @internal
 */
#[CoversClass(DailyNewUserVisitPvRepository::class)]
#[RunTestsInSeparateProcesses]
final class DailyNewUserVisitPvRepositoryTest extends AbstractRepositoryTestCase
{
    private DailyNewUserVisitPvRepository $repository;

    private DailyNewUserVisitPv $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(DailyNewUserVisitPvRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(DailyNewUserVisitPvRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getVisitPv(), $result->getVisitPv());
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
        self::assertContainsOnlyInstancesOf(DailyNewUserVisitPv::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['visitPv' => $this->entity->getVisitPv()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($this->entity->getVisitPv(), $results[0]->getVisitPv());
    }

    public function testFindByWithNullCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setAccount(null);
        $testEntity->setRemark(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testFindOneBy(): void
    {
        $this->repository->save($this->entity);

        $result = $this->repository->findOneBy(['visitPv' => $this->entity->getVisitPv()]);
        self::assertNotNull($result);

        self::assertEquals($this->entity->getVisitPv(), $result->getVisitPv());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['visitPv' => 99999]);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertNotNull($savedEntity);
        self::assertEquals($this->entity->getVisitPv(), $savedEntity->getVisitPv());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        // 对于使用 Snowflake ID 生成器的实体，ID 会在 save 时立即生成
        // 但我们可以检查其他属性是否正确保存
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);
        self::assertEquals($this->entity->getVisitPv(), $result->getVisitPv());

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

    private function createTestEntity(): DailyNewUserVisitPv
    {
        $entity = new DailyNewUserVisitPv();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setVisitPv(100);
        $entity->setVisitUv(50);
        $entity->setRemark('test-remark');

        return $entity;
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setVisitPv(888);

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(DailyNewUserVisitPv::class, $savedEntity);
        self::assertEquals(888, $savedEntity->getVisitPv());
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
        $testEntity->setRemark(null);
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
        $entity1->setDate(new \DateTimeImmutable('2024-01-20'));
        $entity1->setVisitPv(300);
        $entity1->setRemark($uniquePrefix . '-entity1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(new \DateTimeImmutable('2024-01-10'));
        $entity2->setVisitPv(100);
        $entity2->setRemark($uniquePrefix . '-entity2');
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $result = $this->repository->findOneBy(
            ['remark' => [$uniquePrefix . '-entity1', $uniquePrefix . '-entity2']],
            ['date' => 'ASC']
        );

        self::assertNotNull($result);
        self::assertEquals('2024-01-10', $result->getDate()->format('Y-m-d'));
    }

    public function testFindByAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity = $this->createTestEntity();
        $entity->setAccount($account);
        $entity->setVisitPv(150);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($account->getId(), $results[0]->getAccount()?->getId());
    }

    public function testFindByNullVisitPvField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setVisitPv(null);
        $entity->setRemark('test-null-visitpv');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['visitPv' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getVisitPv());
        }
    }

    public function testFindByNullRemarkField(): void
    {
        $entity = $this->createTestEntity();
        $entity->setRemark(null);
        $entity->setVisitPv(200);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['remark' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getRemark());
        }
    }

    public function testCountWithAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitPv(100);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitPv(200);
        $this->repository->save($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullVisitPv(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setVisitPv(null);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setRemark('test-count-null-visitpv-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setVisitPv(null);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setRemark('test-count-null-visitpv-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['visitPv' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullRemark(): void
    {
        $uniqueMarker = 'test-count-null-remark-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setRemark(null);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity1->setVisitPv(100);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setRemark(null);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17'));
        $entity2->setVisitPv(200);
        $this->repository->save($entity2);

        // 由于 remark 为 null，我们无法通过 remark 来区分测试数据
        // 但我们可以通过其他字段来限制范围
        $count = $this->repository->count([
            'remark' => null,
            'visitPv' => [100, 200], // 使用 visitPv 来限制范围
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
        $uniquePrefix = 'test-sorting-visitpv-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setVisitPv(300);
        $entity1->setVisitUv(100);
        $entity1->setRemark($uniquePrefix . '-entity1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setVisitPv(100);
        $entity2->setVisitUv(200);
        $entity2->setRemark($uniquePrefix . '-entity2');
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $result = $this->repository->findOneBy(
            ['remark' => [$uniquePrefix . '-entity1', $uniquePrefix . '-entity2']],
            ['visitPv' => 'ASC']
        );

        self::assertNotNull($result);
        self::assertEquals(100, $result->getVisitPv());

        $resultDesc = $this->repository->findOneBy(
            ['remark' => [$uniquePrefix . '-entity1', $uniquePrefix . '-entity2']],
            ['visitPv' => 'DESC']
        );
        self::assertInstanceOf(DailyNewUserVisitPv::class, $resultDesc);
        self::assertEquals(300, $resultDesc->getVisitPv());
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16')); // 使用不同的日期避免唯一约束冲突
        $entity1->setRemark('test-count-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17')); // 使用不同的日期避免唯一约束冲突
        $entity2->setRemark('test-count-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setRemark('test-no-account');
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setDate(new \DateTimeImmutable('2024-01-16')); // 使用不同的日期避免唯一约束冲突
        $entity1->setRemark('test-find-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setDate(new \DateTimeImmutable('2024-01-17')); // 使用不同的日期避免唯一约束冲突
        $entity2->setRemark('test-find-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setRemark('test-no-account-association');
        $this->repository->save($otherEntity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals($account->getId(), $result->getAccount()?->getId());
        }
    }

    public function testFindByWithSpecificDate(): void
    {
        $testDate = new \DateTimeImmutable('2024-01-01');

        $entity1 = $this->createTestEntity();
        $entity1->setDate($testDate);
        $entity1->setVisitPv(100);
        $entity1->setRemark('test-specific-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate($testDate);
        $entity2->setVisitPv(200);
        $entity2->setRemark('test-specific-date-2');
        $this->repository->save($entity2);

        $entityWithDifferentDate = $this->createTestEntity();
        $entityWithDifferentDate->setDate(new \DateTimeImmutable('2024-02-01'));
        $entityWithDifferentDate->setVisitPv(300);
        $this->repository->save($entityWithDifferentDate);

        // 使用更具体的条件来避免其他测试数据的干扰
        $results = $this->repository->findBy([
            'date' => $testDate,
            'remark' => ['test-specific-date-1', 'test-specific-date-2'],
        ]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals($testDate->format('Y-m-d'), $result->getDate()->format('Y-m-d'));
        }
    }

    public function testCountWithSpecificDate(): void
    {
        $testDate = new \DateTimeImmutable('2024-01-01');

        $entity1 = $this->createTestEntity();
        $entity1->setDate($testDate);
        $entity1->setVisitPv(100);
        $entity1->setRemark('test-count-specific-date-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate($testDate);
        $entity2->setVisitPv(200);
        $entity2->setRemark('test-count-specific-date-2');
        $this->repository->save($entity2);

        $entityWithDifferentDate = $this->createTestEntity();
        $entityWithDifferentDate->setDate(new \DateTimeImmutable('2024-02-01'));
        $entityWithDifferentDate->setVisitPv(300);
        $this->repository->save($entityWithDifferentDate);

        // 使用更具体的条件来避免其他测试数据的干扰
        $count = $this->repository->count([
            'date' => $testDate,
            'remark' => ['test-count-specific-date-1', 'test-count-specific-date-2'],
        ]);

        self::assertEquals(2, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new DailyNewUserVisitPv();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setVisitUv(0);

        return $entity;
    }

    /**
     * @return DailyNewUserVisitPvRepository
     */
    protected function getRepository(): DailyNewUserVisitPvRepository
    {
        return $this->repository;
    }
}

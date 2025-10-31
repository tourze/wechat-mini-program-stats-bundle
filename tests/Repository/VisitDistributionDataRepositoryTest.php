<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\VisitDistributionData;
use WechatMiniProgramStatsBundle\Repository\VisitDistributionDataRepository;

/**
 * @internal
 */
#[CoversClass(VisitDistributionDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class VisitDistributionDataRepositoryTest extends AbstractRepositoryTestCase
{
    private VisitDistributionDataRepository $repository;

    private VisitDistributionData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(VisitDistributionDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(VisitDistributionDataRepository::class));
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setSceneId('save-test-scene');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(VisitDistributionData::class, $savedEntity);
        self::assertEquals('save-test-scene', $savedEntity->getSceneId());
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

    private function createTestEntity(): VisitDistributionData
    {
        $entity = new VisitDistributionData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setType('test-type-' . uniqid());
        $entity->setSceneId('test-scene-' . uniqid());
        $entity->setSceneIdPv('test-pv-' . uniqid());

        return $entity;
    }

    public function testFindOneByWithSorting(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setType('zebra-type-' . uniqid());
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setType('alpha-type-' . uniqid());
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['type' => 'ASC']);

        $resultDesc = $this->repository->findOneBy([], ['type' => 'DESC']);
        self::assertInstanceOf(VisitDistributionData::class, $resultDesc);
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
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
        $entity1->setAccount($account);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
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
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
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

    public function testFindByWithNullType(): void
    {
        $unique = uniqid();
        $entity1 = $this->createTestEntity();
        $entity1->setType(null);
        $entity1->setSceneId('null-type-test-1-' . $unique);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setType(null);
        $entity2->setSceneId('null-type-test-2-' . $unique);
        $this->repository->save($entity2);

        $entityWithType = $this->createTestEntity();
        $entityWithType->setType('test-with-type');
        $entityWithType->setSceneId('with-type-test-' . $unique);
        $this->repository->save($entityWithType);

        $nullTypeResults = $this->repository->findBy(['type' => null]);
        $nullTypeCount = count($nullTypeResults);

        self::assertGreaterThanOrEqual(2, $nullTypeCount);

        // Verify at least our 2 entities are in there
        $ourEntities = 0;
        foreach ($nullTypeResults as $result) {
            self::assertNull($result->getType());
            if ($result->getSceneId() === 'null-type-test-1-' . $unique
                || $result->getSceneId() === 'null-type-test-2-' . $unique) {
                ++$ourEntities;
            }
        }
        self::assertEquals(2, $ourEntities);
    }

    public function testCountWithNullDateField(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setDate(null);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setDate(null);
        $this->repository->save($entity2);

        $entityWithDate = $this->createTestEntity();
        $entityWithDate->setDate(new \DateTimeImmutable('2024-01-01'));
        $this->repository->save($entityWithDate);

        $count = $this->repository->count(['date' => null]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullTypeField(): void
    {
        $unique = uniqid();
        $entity1 = $this->createTestEntity();
        $entity1->setType(null);
        $entity1->setSceneId('count-null-type-1-' . $unique);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setType(null);
        $entity2->setSceneId('count-null-type-2-' . $unique);
        $this->repository->save($entity2);

        $entityWithType = $this->createTestEntity();
        $entityWithType->setType('test-with-type-count');
        $entityWithType->setSceneId('with-type-count-' . $unique);
        $this->repository->save($entityWithType);

        $count = $this->repository->count(['type' => null]);

        self::assertGreaterThanOrEqual(2, $count);
    }

    private function createAccount(): Account
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret_' . uniqid());
        $account->setValid(true);

        $persistedAccount = $this->persistAndFlush($account);
        self::assertInstanceOf(Account::class, $persistedAccount);

        return $persistedAccount;
    }

    protected function createNewEntity(): object
    {
        $entity = new VisitDistributionData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setType('test-type-' . uniqid());
        $entity->setSceneId('test-scene-' . uniqid());
        $entity->setSceneIdPv('test-pv-' . uniqid());

        return $entity;
    }

    /**
     * @return VisitDistributionDataRepository
     */
    protected function getRepository(): VisitDistributionDataRepository
    {
        return $this->repository;
    }
}

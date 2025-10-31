<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;
use WechatMiniProgramStatsBundle\Repository\WeeklyVisitTrendRepository;

/**
 * @internal
 */
#[CoversClass(WeeklyVisitTrendRepository::class)]
#[RunTestsInSeparateProcesses]
final class WeeklyVisitTrendRepositoryTest extends AbstractRepositoryTestCase
{
    private WeeklyVisitTrendRepository $repository;

    private WeeklyVisitTrend $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(WeeklyVisitTrendRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(WeeklyVisitTrendRepository::class));
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setSessionCnt('save-test-count');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(WeeklyVisitTrend::class, $savedEntity);
        self::assertEquals('save-test-count', $savedEntity->getSessionCnt());
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

    private function createTestEntity(): WeeklyVisitTrend
    {
        $unique = uniqid();
        $entity = new WeeklyVisitTrend();
        $entity->setBeginDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setEndDate(new \DateTimeImmutable('2024-01-21'));
        $entity->setSessionCnt('100-' . $unique);
        $entity->setVisitPv('50-' . $unique);
        $entity->setVisitUv('30-' . $unique);
        $entity->setVisitUvNew('20-' . $unique);
        $entity->setStayTimeUv('1800-' . $unique);
        $entity->setStayTimeSession('900-' . $unique);
        $entity->setVisitDepth('2.5-' . $unique);

        return $entity;
    }

    public function testFindOneByWithSorting(): void
    {
        $unique1 = uniqid();
        $entity1 = $this->createTestEntity();
        $entity1->setBeginDate(new \DateTimeImmutable('2024-01-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-01-07'));
        $entity1->setSessionCnt('zebra-count-' . $unique1);
        $entity1->setVisitPv('100-' . $unique1);
        $this->repository->save($entity1);

        $unique2 = uniqid();
        $entity2 = $this->createTestEntity();
        $entity2->setBeginDate(new \DateTimeImmutable('2024-01-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-01-14'));
        $entity2->setSessionCnt('alpha-count-' . $unique2);
        $entity2->setVisitPv('200-' . $unique2);
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy([], ['sessionCnt' => 'ASC']);

        $resultDesc = $this->repository->findOneBy([], ['sessionCnt' => 'DESC']);
        self::assertInstanceOf(WeeklyVisitTrend::class, $resultDesc);
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setBeginDate(new \DateTimeImmutable('2024-01-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-01-07'));
        $entity1->setSessionCnt('test-count-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setBeginDate(new \DateTimeImmutable('2024-01-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-01-14'));
        $entity2->setSessionCnt('test-count-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setBeginDate(new \DateTimeImmutable('2024-02-01'));
        $otherEntity->setEndDate(new \DateTimeImmutable('2024-02-07'));
        $otherEntity->setSessionCnt('test-no-account');
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setBeginDate(new \DateTimeImmutable('2024-03-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-03-07'));
        $entity1->setSessionCnt('test-find-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setBeginDate(new \DateTimeImmutable('2024-03-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-03-14'));
        $entity2->setSessionCnt('test-find-account-association-2');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setBeginDate(new \DateTimeImmutable('2024-04-01'));
        $otherEntity->setEndDate(new \DateTimeImmutable('2024-04-07'));
        $otherEntity->setSessionCnt('test-no-account-association');
        $this->repository->save($otherEntity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals($account->getId(), $result->getAccount()?->getId());
        }
    }

    public function testFindByWithNullAccount(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setAccount(null);
        $entity1->setBeginDate(new \DateTimeImmutable('2024-05-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-05-07'));
        $entity1->setSessionCnt('test-null-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount(null);
        $entity2->setBeginDate(new \DateTimeImmutable('2024-05-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-05-14'));
        $entity2->setSessionCnt('test-null-account-2');
        $this->repository->save($entity2);

        $entityWithAccount = $this->createTestEntity();
        $entityWithAccount->setAccount($this->createAccount());
        $entityWithAccount->setBeginDate(new \DateTimeImmutable('2024-05-15'));
        $entityWithAccount->setEndDate(new \DateTimeImmutable('2024-05-21'));
        $entityWithAccount->setSessionCnt('test-with-account');
        $this->repository->save($entityWithAccount);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(2, count($results));

        $nullAccountEntities = 0;
        foreach ($results as $result) {
            self::assertNull($result->getAccount());
            if ('test-null-account-1' === $result->getSessionCnt() || 'test-null-account-2' === $result->getSessionCnt()) {
                ++$nullAccountEntities;
            }
        }
        self::assertEquals(2, $nullAccountEntities);
    }

    public function testFindByWithSpecificDateRange(): void
    {
        $unique = uniqid();
        $entity1 = $this->createTestEntity();
        $entity1->setBeginDate(new \DateTimeImmutable('2024-08-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-08-07'));
        $entity1->setSessionCnt('test-week1-' . $unique);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setBeginDate(new \DateTimeImmutable('2024-08-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-08-14'));
        $entity2->setSessionCnt('test-week2-' . $unique);
        $this->repository->save($entity2);

        $entityDifferent = $this->createTestEntity();
        $entityDifferent->setBeginDate(new \DateTimeImmutable('2024-08-15'));
        $entityDifferent->setEndDate(new \DateTimeImmutable('2024-08-21'));
        $entityDifferent->setSessionCnt('test-different-month-' . $unique);
        $this->repository->save($entityDifferent);

        $results = $this->repository->findBy(['beginDate' => new \DateTimeImmutable('2024-08-01')]);

        self::assertCount(1, $results);
        foreach ($results as $result) {
            self::assertEquals('2024-08-01', $result->getBeginDate()->format('Y-m-d'));
        }
    }

    public function testCountWithNullAccount(): void
    {
        $entity1 = $this->createTestEntity();
        $entity1->setAccount(null);
        $entity1->setBeginDate(new \DateTimeImmutable('2024-06-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-06-07'));
        $entity1->setSessionCnt('test-count-null-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount(null);
        $entity2->setBeginDate(new \DateTimeImmutable('2024-06-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-06-14'));
        $entity2->setSessionCnt('test-count-null-account-2');
        $this->repository->save($entity2);

        $entityWithAccount = $this->createTestEntity();
        $entityWithAccount->setAccount($this->createAccount());
        $entityWithAccount->setBeginDate(new \DateTimeImmutable('2024-06-15'));
        $entityWithAccount->setEndDate(new \DateTimeImmutable('2024-06-21'));
        $entityWithAccount->setSessionCnt('test-with-account-count');
        $this->repository->save($entityWithAccount);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(2, $count);
    }

    public function testCountWithSpecificSessionCnt(): void
    {
        $unique = uniqid();
        $entity1 = $this->createTestEntity();
        $entity1->setBeginDate(new \DateTimeImmutable('2024-07-01'));
        $entity1->setEndDate(new \DateTimeImmutable('2024-07-07'));
        $entity1->setSessionCnt('test-specific-count-' . $unique);
        $entity1->setVisitPv('count-test-1-' . $unique);
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setBeginDate(new \DateTimeImmutable('2024-07-08'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-07-14'));
        $entity2->setSessionCnt('test-specific-count-' . $unique);
        $entity2->setVisitPv('count-test-2-' . $unique);
        $this->repository->save($entity2);

        $entityDifferent = $this->createTestEntity();
        $entityDifferent->setBeginDate(new \DateTimeImmutable('2024-07-15'));
        $entityDifferent->setEndDate(new \DateTimeImmutable('2024-07-21'));
        $entityDifferent->setSessionCnt('different-count-' . $unique);
        $entityDifferent->setVisitPv('count-test-different-' . $unique);
        $this->repository->save($entityDifferent);

        $count = $this->repository->count(['sessionCnt' => 'test-specific-count-' . $unique]);

        self::assertEquals(2, $count);
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
        $unique = uniqid();
        $entity = new WeeklyVisitTrend();
        $entity->setBeginDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setEndDate(new \DateTimeImmutable('2024-01-21'));
        $entity->setSessionCnt('100-' . $unique);
        $entity->setVisitPv('50-' . $unique);
        $entity->setVisitUv('30-' . $unique);
        $entity->setVisitUvNew('20-' . $unique);
        $entity->setStayTimeUv('1800-' . $unique);
        $entity->setStayTimeSession('900-' . $unique);
        $entity->setVisitDepth('2.5-' . $unique);

        return $entity;
    }

    /**
     * @return WeeklyVisitTrendRepository
     */
    protected function getRepository(): WeeklyVisitTrendRepository
    {
        return $this->repository;
    }
}

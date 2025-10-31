<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;
use WechatMiniProgramStatsBundle\Repository\MonthlyVisitTrendRepository;

/**
 * @internal
 */
#[CoversClass(MonthlyVisitTrendRepository::class)]
#[RunTestsInSeparateProcesses]
final class MonthlyVisitTrendRepositoryTest extends AbstractRepositoryTestCase
{
    private MonthlyVisitTrendRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(MonthlyVisitTrendRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(MonthlyVisitTrendRepository::class));
    }

    protected function createNewEntity(): object
    {
        return $this->createValidEntity();
    }

    /**
     * @return MonthlyVisitTrendRepository
     */
    protected function getRepository(): MonthlyVisitTrendRepository
    {
        return $this->repository;
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createValidEntity();

        $this->repository->save($entity);

        self::assertGreaterThan(0, $entity->getId());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = $this->createValidEntity();

        $this->repository->save($entity, false);

        // Entity should not have an ID when save without flush
        self::assertSame(0, $entity->getId());
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createValidEntity();
        $this->repository->save($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    public function testFindByWithNullAccountShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setAccount(null);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testCountWithNullAccountShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setAccount(null);
        $this->repository->save($entity);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithNullVisitUvNewFieldShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setVisitUvNew(null);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['visitUvNew' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testCountWithNullVisitUvNewFieldShouldWork(): void
    {
        $entity = $this->createValidEntity();
        $entity->setVisitUvNew(null);
        $this->repository->save($entity);

        $count = $this->repository->count(['visitUvNew' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByWithOrderBy(): void
    {
        $entity1 = $this->createValidEntity();
        $entity1->setSessionCnt('300');
        $entity1->setVisitPv('unique-1');
        $this->repository->save($entity1, false);

        $entity2 = $this->createValidEntity();
        $entity2->setBeginDate(new \DateTimeImmutable('2024-02-01'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-02-28'));
        $entity2->setSessionCnt('100');
        $entity2->setVisitPv('unique-2');
        $this->repository->save($entity2, false);

        self::getEntityManager()->flush();

        // 使用唯一的查询条件来确保测试的准确性
        $results = $this->repository->findBy(['visitPv' => ['unique-1', 'unique-2']], ['sessionCnt' => 'ASC']);

        self::assertNotEmpty($results);
        self::assertEquals('100', $results[0]->getSessionCnt());
    }

    public function testFindByAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity = $this->createValidEntity();
        $entity->setAccount($account);
        $entity->setSessionCnt('unique-session-123');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($account->getId(), $results[0]->getAccount()?->getId());
    }

    public function testCountWithAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createValidEntity();
        $entity1->setAccount($account);
        $entity1->setSessionCnt('count-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createValidEntity();
        $entity2->setAccount($account);
        $entity2->setBeginDate(new \DateTimeImmutable('2024-02-01'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-02-28'));
        $entity2->setSessionCnt('count-account-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['account' => $account]);

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

    private function createValidEntity(): MonthlyVisitTrend
    {
        $entity = new MonthlyVisitTrend();
        $entity->setBeginDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setEndDate(new \DateTimeImmutable('2024-01-31'));
        $entity->setSessionCnt('100');
        $entity->setVisitPv('200');
        $entity->setVisitUv('150');
        $entity->setVisitUvNew('50');
        $entity->setStayTimeUv('300');
        $entity->setStayTimeSession('250');
        $entity->setVisitDepth('2.5');

        return $entity;
    }

    public function testFindOneByWithSorting(): void
    {
        $entity1 = $this->createValidEntity();
        $entity1->setSessionCnt('200');
        $entity1->setVisitPv('zebra-unique-1');
        $this->repository->save($entity1);

        $entity2 = $this->createValidEntity();
        $entity2->setBeginDate(new \DateTimeImmutable('2024-02-01'));
        $entity2->setEndDate(new \DateTimeImmutable('2024-02-28'));
        $entity2->setSessionCnt('100');
        $entity2->setVisitPv('alpha-unique-2');
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['visitPv' => ['zebra-unique-1', 'alpha-unique-2']], ['sessionCnt' => 'ASC']);
        self::assertNotNull($result);

        self::assertEquals('100', $result->getSessionCnt());

        $resultDesc = $this->repository->findOneBy(['visitPv' => ['zebra-unique-1', 'alpha-unique-2']], ['sessionCnt' => 'DESC']);
        self::assertInstanceOf(MonthlyVisitTrend::class, $resultDesc);
        self::assertEquals('200', $resultDesc->getSessionCnt());
    }
}

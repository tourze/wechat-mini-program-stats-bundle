<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\Performance;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;
use WechatMiniProgramStatsBundle\Repository\PerformanceRepository;

/**
 * @internal
 */
#[CoversClass(PerformanceRepository::class)]
#[RunTestsInSeparateProcesses]
final class PerformanceRepositoryTest extends AbstractRepositoryTestCase
{
    private PerformanceRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(PerformanceRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(PerformanceRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new Performance();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test PerformanceRepository ' . uniqid());
    }

    /**
     * @return PerformanceRepository
     */
    protected function getRepository(): PerformanceRepository
    {
        return self::getService(PerformanceRepository::class);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createValidEntity();

        $this->repository->save($entity);

        self::assertGreaterThan(0, $entity->getId());
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

    public function testFindOneByWithOrderBy(): void
    {
        $uniquePrefix = 'test_order_' . uniqid();

        $entity1 = $this->createValidEntity();
        $entity1->setName($uniquePrefix . '_zebra-name');
        $this->repository->save($entity1);

        $entity2 = $this->createValidEntity();
        $entity2->setName($uniquePrefix . '_alpha-name');
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['name' => $uniquePrefix . '_alpha-name'], ['name' => 'ASC']);

        self::assertNotNull($result);
        self::assertEquals($uniquePrefix . '_alpha-name', $result->getName());
    }

    public function testFindByAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity = $this->createValidEntity();
        $entity->setAccount($account);
        $entity->setName('test-with-account');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($account->getId(), $results[0]->getAccount()?->getId());
    }

    public function testFindByNullNameField(): void
    {
        $entity = new Performance();
        $entity->setNameZh('test-null-name');
        $entity->setModule(PerformanceModule::TYPE_16);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['name' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getName());
        }
    }

    public function testFindByNullNameZhField(): void
    {
        $entity = new Performance();
        $entity->setName('test-null-namezh');
        $entity->setModule(PerformanceModule::TYPE_16);
        $this->repository->save($entity);

        $results = $this->repository->findBy(['nameZh' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getNameZh());
        }
    }

    public function testFindByNullModuleField(): void
    {
        $entity = new Performance();
        $entity->setName('test-null-module');
        $entity->setNameZh('测试空模块');
        $this->repository->save($entity);

        $results = $this->repository->findBy(['module' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getModule());
        }
    }

    public function testCountWithAccountRelation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createValidEntity();
        $entity1->setAccount($account);
        $entity1->setName('test-count-account-1');
        $this->repository->save($entity1);

        $entity2 = $this->createValidEntity();
        $entity2->setAccount($account);
        $entity2->setName('test-count-account-2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testCountWithNullName(): void
    {
        $uniqueMarker = 'test-count-null-name-' . uniqid();

        $entity1 = new Performance();
        $entity1->setNameZh($uniqueMarker . '-1');
        $entity1->setModule(PerformanceModule::TYPE_16);
        $this->repository->save($entity1);

        $entity2 = new Performance();
        $entity2->setNameZh($uniqueMarker . '-2');
        $entity2->setModule(PerformanceModule::TYPE_17);
        $this->repository->save($entity2);

        $count = $this->repository->count(['name' => null, 'nameZh' => $uniqueMarker . '-1']);

        self::assertEquals(1, $count);
    }

    public function testCountWithNullNameZh(): void
    {
        $uniqueMarker = 'test-count-null-namezh-' . uniqid();

        $entity1 = new Performance();
        $entity1->setName($uniqueMarker . '-1');
        $entity1->setModule(PerformanceModule::TYPE_16);
        $this->repository->save($entity1);

        $entity2 = new Performance();
        $entity2->setName($uniqueMarker . '-2');
        $entity2->setModule(PerformanceModule::TYPE_17);
        $this->repository->save($entity2);

        $count = $this->repository->count(['nameZh' => null, 'name' => $uniqueMarker . '-1']);

        self::assertEquals(1, $count);
    }

    public function testCountWithNullModule(): void
    {
        $uniqueMarker = 'test-count-null-module-' . uniqid();

        $entity1 = new Performance();
        $entity1->setName($uniqueMarker . '-1');
        $entity1->setNameZh('测试计数空模块1');
        $this->repository->save($entity1);

        $entity2 = new Performance();
        $entity2->setName($uniqueMarker . '-2');
        $entity2->setNameZh('测试计数空模块2');
        $this->repository->save($entity2);

        $count = $this->repository->count(['module' => null, 'name' => $uniqueMarker . '-1']);

        self::assertEquals(1, $count);
    }

    private function createValidEntity(): Performance
    {
        $entity = new Performance();
        $entity->setName('test-performance');
        $entity->setNameZh('测试性能');
        $entity->setModule(PerformanceModule::TYPE_16);

        return $entity;
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
}

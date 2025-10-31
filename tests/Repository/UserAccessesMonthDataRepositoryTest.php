<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;
use WechatMiniProgramStatsBundle\Repository\UserAccessesMonthDataRepository;

/**
 * @internal
 */
#[CoversClass(UserAccessesMonthDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserAccessesMonthDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserAccessesMonthDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserAccessesMonthDataRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserAccessesMonthDataRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new UserAccessesMonthData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test UserAccessesMonthDataRepository ' . uniqid());
    }

    /**
     * @return UserAccessesMonthDataRepository
     */
    protected function getRepository(): UserAccessesMonthDataRepository
    {
        return self::getService(UserAccessesMonthDataRepository::class);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();

        $this->repository->save($entity);

        self::assertNotNull($entity->getId());
        self::assertGreaterThan(0, $entity->getId());
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();
        $this->repository->save($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    private function createValidEntity(): UserAccessesMonthData
    {
        $entity = new UserAccessesMonthData();
        $entity->setDate('2024-01-01');
        $entity->setRetentionMark('test-retention');
        $entity->setType('month');
        $entity->setUserNumber('150');

        return $entity;
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;
use WechatMiniProgramStatsBundle\Repository\UserAccessesWeekDataRepository;

/**
 * @internal
 */
#[CoversClass(UserAccessesWeekDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserAccessesWeekDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserAccessesWeekDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserAccessesWeekDataRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserAccessesWeekDataRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new UserAccessesWeekData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test UserAccessesWeekDataRepository ' . uniqid());
    }

    /**
     * @return UserAccessesWeekDataRepository
     */
    protected function getRepository(): UserAccessesWeekDataRepository
    {
        return self::getService(UserAccessesWeekDataRepository::class);
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

    private function createValidEntity(): UserAccessesWeekData
    {
        $entity = new UserAccessesWeekData();
        $entity->setDate('2024-01-01');
        $entity->setRetentionMark('test-retention');
        $entity->setType('week');
        $entity->setUserNumber('120');

        return $entity;
    }
}

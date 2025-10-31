<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitAgeDataRepository;

/**
 * @internal
 */
#[CoversClass(UserPortraitAgeDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserPortraitAgeDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserPortraitAgeDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserPortraitAgeDataRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserPortraitAgeDataRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new UserPortraitAgeData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test UserPortraitAgeDataRepository ' . uniqid());
    }

    /**
     * @return UserPortraitAgeDataRepository
     */
    protected function getRepository(): UserPortraitAgeDataRepository
    {
        return self::getService(UserPortraitAgeDataRepository::class);
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

    private function createValidEntity(): UserPortraitAgeData
    {
        $entity = new UserPortraitAgeData();
        $entity->setDate('2024-01-01');
        $entity->setType('age');
        $entity->setName('25-34');
        $entity->setValue('150');
        $entity->setValueId('age_25_34');

        return $entity;
    }
}

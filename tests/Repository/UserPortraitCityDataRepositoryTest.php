<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitCityDataRepository;

/**
 * @internal
 */
#[CoversClass(UserPortraitCityDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserPortraitCityDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserPortraitCityDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserPortraitCityDataRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserPortraitCityDataRepository::class));
    }

    protected function createNewEntity(): object
    {
        return new UserPortraitCityData();

        // 设置基本字段 - 需要根据实际实体调整
        // $entity->setName('Test UserPortraitCityDataRepository ' . uniqid());
    }

    /**
     * @return UserPortraitCityDataRepository
     */
    protected function getRepository(): UserPortraitCityDataRepository
    {
        return self::getService(UserPortraitCityDataRepository::class);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();

        $this->repository->save($entity);

        $entityId = $entity->getId();
        self::assertNotNull($entityId);
        self::assertGreaterThan(0, $entityId);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $repository = $this->getRepository();
        $entity = $this->createValidEntity();
        $this->repository->save($entity);
        $entityId = $entity->getId();
        self::assertNotNull($entityId);

        $this->repository->remove($entity);

        $result = $this->repository->find($entityId);
        self::assertNull($result);
    }

    private function createValidEntity(): UserPortraitCityData
    {
        $entity = new UserPortraitCityData();
        $entity->setDate('2024-01-01');
        $entity->setType('city');
        $entity->setName('北京市');
        $entity->setValue('200');
        $entity->setValueId('city_beijing');

        return $entity;
    }
}

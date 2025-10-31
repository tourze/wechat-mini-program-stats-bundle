<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

/**
 * @internal
 */
#[CoversClass(UserAccessPageDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class UserAccessPageDataRepositoryTest extends AbstractRepositoryTestCase
{
    private UserAccessPageDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(UserAccessPageDataRepository::class);
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(UserAccessPageDataRepository::class));
    }

    protected function createNewEntity(): UserAccessPageData
    {
        $entity = new UserAccessPageData();
        $entity->setDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setPagePath('/test/page');
        $entity->setPageVisitPv(100);

        return $entity;
    }

    /**
     * @return UserAccessPageDataRepository
     */
    protected function getRepository(): UserAccessPageDataRepository
    {
        return self::getService(UserAccessPageDataRepository::class);
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

    private function createValidEntity(): UserAccessPageData
    {
        $entity = new UserAccessPageData();
        $entity->setDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setPagePath('/pages/index');
        $entity->setPageVisitPv(300);
        $entity->setPageVisitUv(250);
        $entity->setPageStayTime(1200.0);
        $entity->setEntryPagePv(150);
        $entity->setExitPagePv(100);
        $entity->setPageSharePv(20);
        $entity->setPageShareUv(18);

        return $entity;
    }
}

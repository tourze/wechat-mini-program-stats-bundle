<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\ORM\Exception\MissingIdentifierField;
use Doctrine\ORM\Persisters\Exception\UnrecognizedField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;

/**
 * @internal
 */
#[CoversClass(DailyRetainDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class DailyRetainDataRepositoryTest extends AbstractRepositoryTestCase
{
    private DailyRetainDataRepository $repository;

    private DailyRetainData $entity;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(DailyRetainDataRepository::class);
        $this->entity = $this->createTestEntity();
    }

    public function testClassExists(): void
    {
        self::assertTrue(class_exists(DailyRetainDataRepository::class));
    }

    public function testFindWithExistingId(): void
    {
        $this->repository->save($this->entity);
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);

        self::assertEquals($this->entity->getId(), $result->getId());
        self::assertEquals($this->entity->getType(), $result->getType());
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
        $secondEntity->setType('weekly');
        $this->repository->save($secondEntity);

        $results = $this->repository->findAll();

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(DailyRetainData::class, $results);
    }

    public function testFindBy(): void
    {
        $this->repository->save($this->entity);

        $results = $this->repository->findBy(['type' => $this->entity->getType()]);

        self::assertGreaterThanOrEqual(1, count($results));
        self::assertEquals($this->entity->getType(), $results[0]->getType());
    }

    public function testFindByWithNullCriteria(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setAccount(null);
        $testEntity->setType(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
    }

    public function testFindOneBy(): void
    {
        $this->repository->save($this->entity);

        $result = $this->repository->findOneBy(['type' => $this->entity->getType()]);
        self::assertNotNull($result);

        self::assertEquals($this->entity->getType(), $result->getType());
    }

    public function testFindOneByWithNonExistentCriteria(): void
    {
        $result = $this->repository->findOneBy(['type' => 'non-existent-type']);
        self::assertNull($result);
    }

    public function testSave(): void
    {
        $this->repository->save($this->entity);

        $savedEntity = $this->repository->find($this->entity->getId());
        self::assertInstanceOf(DailyRetainData::class, $savedEntity);
        self::assertEquals($this->entity->getType(), $savedEntity->getType());
    }

    public function testSaveWithoutFlush(): void
    {
        $this->repository->save($this->entity, false);

        // 对于使用 Snowflake ID 生成器的实体，ID 会在 save 时立即生成
        // 但我们可以检查其他属性是否正确保存
        $result = $this->repository->find($this->entity->getId());
        self::assertNotNull($result);
        self::assertEquals($this->entity->getType(), $result->getType());

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

    private function createTestEntity(): DailyRetainData
    {
        $entity = new DailyRetainData();
        $entity->setDate(new \DateTimeImmutable('2024-01-15'));
        $entity->setType('daily');
        $entity->setUserNumber('150');

        return $entity;
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $testEntity = $this->createTestEntity();
        $testEntity->setType('save-test-type');

        $this->repository->save($testEntity);

        $savedEntity = $this->repository->find($testEntity->getId());
        self::assertInstanceOf(DailyRetainData::class, $savedEntity);
        self::assertEquals('save-test-type', $savedEntity->getType());
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
        $testEntity->setType(null);
        $this->repository->save($testEntity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertGreaterThanOrEqual(1, count($results));
        foreach ($results as $result) {
            self::assertNull($result->getAccount());
        }
    }

    public function testFindOneByWithSorting(): void
    {
        $uniquePrefix = 'test-sorting-' . uniqid();

        $entity1 = $this->createTestEntity();
        $entity1->setType($uniquePrefix . '-zebra-type');
        $entity1->setUserNumber('100');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setType($uniquePrefix . '-alpha-type');
        $entity2->setUserNumber('200');
        $entity2->setDate(new \DateTimeImmutable('2024-01-16'));
        $this->repository->save($entity2);

        // 使用更具体的条件来避免其他测试数据的干扰
        $result = $this->repository->findOneBy(
            ['userNumber' => ['100', '200']],
            ['type' => 'ASC']
        );
        self::assertNotNull($result);

        self::assertEquals($uniquePrefix . '-alpha-type', $result->getType());

        $resultDesc = $this->repository->findOneBy(
            ['userNumber' => ['100', '200']],
            ['type' => 'DESC']
        );
        self::assertInstanceOf(DailyRetainData::class, $resultDesc);
        self::assertEquals($uniquePrefix . '-zebra-type', $resultDesc->getType());
    }

    public function testCountWithAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setUserNumber('test-count-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setUserNumber('test-count-account-association-2');
        $entity2->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity2->setType('different-type');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setUserNumber('test-no-account');
        $otherEntity->setDate(new \DateTimeImmutable('2024-01-17'));
        $this->repository->save($otherEntity);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByAccountAssociation(): void
    {
        $account = $this->createAccount();

        $entity1 = $this->createTestEntity();
        $entity1->setAccount($account);
        $entity1->setUserNumber('test-find-account-association-1');
        $this->repository->save($entity1);

        $entity2 = $this->createTestEntity();
        $entity2->setAccount($account);
        $entity2->setUserNumber('test-find-account-association-2');
        $entity2->setDate(new \DateTimeImmutable('2024-01-16'));
        $entity2->setType('different-type');
        $this->repository->save($entity2);

        $otherEntity = $this->createTestEntity();
        $otherEntity->setUserNumber('test-no-account-association');
        $otherEntity->setDate(new \DateTimeImmutable('2024-01-17'));
        $this->repository->save($otherEntity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertCount(2, $results);
        foreach ($results as $result) {
            self::assertEquals($account->getId(), $result->getAccount()?->getId());
        }
    }

    private function createAccount(): Account
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret_' . uniqid());
        $account->setValid(true);

        $entityManager = self::getEntityManager();
        $entityManager->persist($account);
        $entityManager->flush();

        return $account;
    }

    protected function createNewEntity(): object
    {
        $uniqueId = uniqid('test_entity_', true);
        $entity = new DailyRetainData();
        $entity->setDate(new \DateTimeImmutable('2024-01-' . (15 + (int) substr($uniqueId, -2) % 10)));
        $entity->setType('daily-' . substr($uniqueId, -8));
        $entity->setUserNumber('100-' . $uniqueId);

        return $entity;
    }

    /**
     * @return DailyRetainDataRepository
     */
    protected function getRepository(): DailyRetainDataRepository
    {
        return $this->repository;
    }

    /**
     * 修复基类中有问题的测试方法，使用更可靠的数据库不可用模拟
     * 由于基类方法是final的，我们创建一个新的测试方法
     */
    public function testFindByWithCorruptedDatabaseShouldThrowException(): void
    {
        $this->expectException(Exception::class);

        // 获取连接信息
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            // 对于SQLite，我们采用更强力的破坏方法
            $connection->close();

            $params = $connection->getParams();
            $dbPath = isset($params['path']) ? trim($params['path'], 'file:') : null;

            if (null === $dbPath) {
                return; // 无法获取数据库路径，跳过此步骤
            }

            if (file_exists($dbPath)) {
                // 彻底删除数据库文件
                unlink($dbPath);

                // 创建一个损坏的文件，包含无效的SQLite头
                $corruptedContent = str_repeat("\x00\xFF\xDE\xAD\xBE\xEF", 2000);
                file_put_contents($dbPath, $corruptedContent, LOCK_EX);

                // 跳过权限操作，因为可能在测试清理时出现问题
            }
        } else {
            // 对于其他数据库，简单关闭连接
            $connection->close();
        }

        // 简化数据库破坏测试：直接关闭连接
        $connection->close();

        // 现在执行查询，应该抛出异常
        $this->repository->findBy([]);
    }
}

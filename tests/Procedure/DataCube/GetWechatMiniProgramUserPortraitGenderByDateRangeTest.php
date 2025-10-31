<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitGenderByDateRange;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramUserPortraitGenderByDateRange::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramUserPortraitGenderByDateRangeTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归

        // 创建数据库表结构
        $this->createSchema();
    }

    /**
     * 创建数据库表结构
     */
    private function createSchema(): void
    {
        $entityManager = self::getEntityManager();
        $schemaTool = new SchemaTool($entityManager);

        $metadata = [
            $entityManager->getClassMetadata(Account::class),
            $entityManager->getClassMetadata(UserPortraitGendersData::class),
        ];

        // 更新数据库结构而不是创建，避免表已存在的错误
        $schemaTool->updateSchema($metadata);
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramUserPortraitGenderByDateRange::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramUserPortraitGenderByDateRange::class);
        $procedure->accountId = 'invalid-account';
        $procedure->startDate = '2023-01-01';
        $procedure->endDate = '2023-01-07';

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute();
    }

    public function testExecuteWithValidData(): void
    {
        // 创建测试用的 Account
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);

        // 创建测试用的 UserPortraitGendersData
        $maleData = new UserPortraitGendersData();
        $maleData->setAccount($account);
        $maleData->setDate('20230101');
        $maleData->setType('visit_uv');
        $maleData->setName('男');
        $maleData->setValue('100');
        self::getEntityManager()->persist($maleData);

        $femaleData = new UserPortraitGendersData();
        $femaleData->setAccount($account);
        $femaleData->setDate('20230101');
        $femaleData->setType('visit_uv');
        $femaleData->setName('女');
        $femaleData->setValue('80');
        self::getEntityManager()->persist($femaleData);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitGenderByDateRange::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->startDate = '2023-01-01';
        $procedure->endDate = '2023-01-02';

        $result = $procedure->execute();

        $expected = [
            'male' => [['date' => '20230101', 'value' => '100']],
            'female' => [['date' => '20230101', 'value' => '80']],
        ];

        self::assertEquals($expected, $result);
    }
}

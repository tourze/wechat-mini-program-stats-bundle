<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitAge;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramUserPortraitAge::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramUserPortraitAgeTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
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
            $entityManager->getClassMetadata(UserPortraitAgeData::class),
        ];

        // 更新数据库结构而不是创建，避免表已存在的错误
        $schemaTool->updateSchema($metadata);
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramUserPortraitAge::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramUserPortraitAge::class);
        $procedure->accountId = 'invalid-account';
        $procedure->day = 1;

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute();
    }

    public function testExecuteWithValidAccountAndDay1(): void
    {
        // 创建测试用的 Account
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);

        // 动态生成昨天的日期
        $yesterday = CarbonImmutable::now()->subDay();
        $dateStr = $yesterday->format('Ymd');

        // 创建测试用的 UserPortraitAgeData
        $ageData1 = new UserPortraitAgeData();
        $ageData1->setAccount($account);
        $ageData1->setDate($dateStr);
        $ageData1->setType('visit_uv');
        $ageData1->setName('18-24岁');
        $ageData1->setValue('100');
        self::getEntityManager()->persist($ageData1);

        $ageData2 = new UserPortraitAgeData();
        $ageData2->setAccount($account);
        $ageData2->setDate($dateStr);
        $ageData2->setType('visit_uv');
        $ageData2->setName('25-35岁');
        $ageData2->setValue('200');
        self::getEntityManager()->persist($ageData2);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitAge::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = 1;

        $result = $procedure->execute();

        $expected = [
            'data' => [
                ['name' => '18-24岁', 'value' => '100'],
                ['name' => '25-35岁', 'value' => '200'],
            ],
        ];

        self::assertEquals($expected, $result);
    }

    public function testExecuteWithEmptyDataCallsService(): void
    {
        // 创建测试用的 Account
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitAge::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = 7;

        $result = $procedure->execute();

        self::assertEquals(['data' => []], $result);
    }

    public function testExecuteWithInvalidDay(): void
    {
        // 创建测试用的 Account
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitAge::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = 99;

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('no data');

        $procedure->execute();
    }
}

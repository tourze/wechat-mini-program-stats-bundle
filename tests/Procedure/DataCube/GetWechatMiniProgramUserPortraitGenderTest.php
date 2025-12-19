<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use Carbon\CarbonImmutable;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramUserPortraitGenderParam;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitGender;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramUserPortraitGender::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramUserPortraitGenderTest extends AbstractProcedureTestCase
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
        self::assertTrue(class_exists(GetWechatMiniProgramUserPortraitGender::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramUserPortraitGender::class);
        $param = new GetWechatMiniProgramUserPortraitGenderParam(
            accountId: 'invalid-account',
            day: 1
        );

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute($param);
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

        // 创建测试用的 UserPortraitGendersData
        $maleData = new UserPortraitGendersData();
        $maleData->setAccount($account);
        $maleData->setDate($dateStr);
        $maleData->setType('visit_uv');
        $maleData->setName('男');
        $maleData->setValue('100');
        self::getEntityManager()->persist($maleData);

        $femaleData = new UserPortraitGendersData();
        $femaleData->setAccount($account);
        $femaleData->setDate($dateStr);
        $femaleData->setType('visit_uv');
        $femaleData->setName('女');
        $femaleData->setValue('80');
        self::getEntityManager()->persist($femaleData);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitGender::class);
        $param = new GetWechatMiniProgramUserPortraitGenderParam(
            accountId: (string) $account->getId(),
            day: 1
        );

        $result = $procedure->execute($param);

        $expected = [
            'data' => [
                ['name' => '女', 'value' => '80'],
                ['name' => '男', 'value' => '100'],
            ],
        ];

        self::assertEquals($expected, $result->toArray());
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

        $procedure = self::getService(GetWechatMiniProgramUserPortraitGender::class);
        $param = new GetWechatMiniProgramUserPortraitGenderParam(
            accountId: (string) $account->getId(),
            day: 7
        );

        $result = $procedure->execute($param);

        self::assertEquals(['data' => []], $result->toArray());
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

        $procedure = self::getService(GetWechatMiniProgramUserPortraitGender::class);
        $param = new GetWechatMiniProgramUserPortraitGenderParam(
            accountId: (string) $account->getId(),
            day: 99
        );

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('no data');

        $procedure->execute($param);
    }
}

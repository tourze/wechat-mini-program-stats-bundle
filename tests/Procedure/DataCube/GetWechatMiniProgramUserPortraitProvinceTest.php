<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;
use WechatMiniProgramStatsBundle\Procedure\DataCube\GetWechatMiniProgramUserPortraitProvince;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramUserPortraitProvince::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramUserPortraitProvinceTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramUserPortraitProvince::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramUserPortraitProvince::class);
        $procedure->accountId = 'invalid-account';
        $procedure->day = 1;

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute();
    }

    public function testExecuteWithValidAccountAndDay1(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);

        $now = CarbonImmutable::now();
        $yesterday = $now->clone()->subDay();
        $date = $yesterday->format('Ymd');

        $provinceData1 = new UserPortraitProvinceData();
        $provinceData1->setAccount($account);
        $provinceData1->setDate($date);
        $provinceData1->setType('visit_uv');
        $provinceData1->setName('北京');
        $provinceData1->setValue('150');
        self::getEntityManager()->persist($provinceData1);

        $provinceData2 = new UserPortraitProvinceData();
        $provinceData2->setAccount($account);
        $provinceData2->setDate($date);
        $provinceData2->setType('visit_uv');
        $provinceData2->setName('上海');
        $provinceData2->setValue('120');
        self::getEntityManager()->persist($provinceData2);

        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitProvince::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = 1;

        $result = $procedure->execute();

        $expected = [
            'data' => [
                ['name' => '北京', 'value' => '150'],
                ['name' => '上海', 'value' => '120'],
            ],
        ];

        // 验证数据结构，不关心顺序
        self::assertArrayHasKey('data', $result);
        self::assertIsArray($result['data']);
        self::assertCount(2, $result['data']);
        self::assertContains(['name' => '北京', 'value' => '150'], $result['data']);
        self::assertContains(['name' => '上海', 'value' => '120'], $result['data']);
    }

    public function testExecuteWithEmptyData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitProvince::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = 30;

        $result = $procedure->execute();

        self::assertEquals(['data' => []], $result);
    }

    public function testExecuteWithInvalidDay(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-app-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramUserPortraitProvince::class);
        $procedure->accountId = (string) $account->getId();
        $procedure->day = 99;

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('no data');

        $procedure->execute();
    }
}

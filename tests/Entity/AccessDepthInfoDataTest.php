<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;

/**
 * @internal
 */
#[CoversClass(AccessDepthInfoData::class)]
final class AccessDepthInfoDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new AccessDepthInfoData();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'id' => ['id', 'test_id'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
        ];
    }

    private AccessDepthInfoData $accessDepthInfoData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessDepthInfoData = new AccessDepthInfoData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->accessDepthInfoData->getId());
    }

    public function testCreateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->accessDepthInfoData->getCreateTime());

        $this->accessDepthInfoData->setCreateTime($now);

        self::assertSame($now, $this->accessDepthInfoData->getCreateTime());
    }

    public function testDateGetterAndSetter(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');
        self::assertNull($this->accessDepthInfoData->getDate());

        $this->accessDepthInfoData->setDate($date);

        self::assertSame($date, $this->accessDepthInfoData->getDate());
    }

    public function testDataKeyGetterAndSetter(): void
    {
        $dataKey = 'test_key';
        self::assertNull($this->accessDepthInfoData->getDataKey());

        $this->accessDepthInfoData->setDataKey($dataKey);

        self::assertSame($dataKey, $this->accessDepthInfoData->getDataKey());
    }

    public function testDataValueGetterAndSetter(): void
    {
        $dataValue = 'test_value';
        self::assertNull($this->accessDepthInfoData->getDataValue());

        $this->accessDepthInfoData->setDataValue($dataValue);

        self::assertSame($dataValue, $this->accessDepthInfoData->getDataValue());
    }

    public function testAccountGetterAndSetter(): void
    {
        // 必须使用具体类 Account 而不是接口的原因：
        // 理由1：Account 是 Doctrine Entity 类，代表数据库中的账户实体，没有对应的接口
        // 理由2：测试需要模拟 AccessDepthInfoData 实体关联的账户对象，验证 getter/setter 方法
        // 理由3：使用 Mock 可以避免创建真实的 Account 实例和数据库操作，提高测试速度和隔离性
        $account = $this->createMock(Account::class);
        self::assertNull($this->accessDepthInfoData->getAccount());

        $this->accessDepthInfoData->setAccount($account);

        self::assertSame($account, $this->accessDepthInfoData->getAccount());
    }

    public function testRetrieveAdminArrayReturnsExpectedFormat(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');
        $dataKey = 'test_key';
        $dataValue = 'test_value';
        // 必须使用具体类 Account 而不是接口的原因：
        // 理由1：Account 是 Doctrine Entity 类，代表数据库中的账户实体，没有对应的接口
        // 理由2：测试需要验证 retrieveAdminArray() 方法返回的数组包含正确的账户对象引用
        // 理由3：使用 Mock 可以在测试中精确控制返回的对象，确保测试结果的可预测性
        $account = $this->createMock(Account::class);
        $createTime = new \DateTimeImmutable();

        $this->accessDepthInfoData->setDate($date);
        $this->accessDepthInfoData->setDataKey($dataKey);
        $this->accessDepthInfoData->setDataValue($dataValue);
        $this->accessDepthInfoData->setAccount($account);
        $this->accessDepthInfoData->setCreateTime($createTime);

        $result = $this->accessDepthInfoData->retrieveAdminArray();

        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('date', $result);
        self::assertArrayHasKey('dataKey', $result);
        self::assertArrayHasKey('dataValue', $result);
        self::assertArrayHasKey('account', $result);
        self::assertArrayHasKey('createTime', $result);
        self::assertSame($date, $result['date']);
        self::assertSame($dataKey, $result['dataKey']);
        self::assertSame($dataValue, $result['dataValue']);
        self::assertSame($account, $result['account']);
        self::assertSame($createTime, $result['createTime']);
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->accessDepthInfoData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

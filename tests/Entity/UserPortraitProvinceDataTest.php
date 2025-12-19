<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;

/**
 * @internal
 */
#[CoversClass(UserPortraitProvinceData::class)]
final class UserPortraitProvinceDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitProvinceData();
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

    private UserPortraitProvinceData $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = new UserPortraitProvinceData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->data->getId());
    }

    public function testCreateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->data->getCreateTime());

        $this->data->setCreateTime($now);

        self::assertSame($now, $this->data->getCreateTime());
    }

    public function testDateGetterAndSetter(): void
    {
        $date = '20230101-20230107';
        self::assertNull($this->data->getDate());

        $this->data->setDate($date);

        self::assertSame($date, $this->data->getDate());
    }

    public function testTypeGetterAndSetter(): void
    {
        $type = 'visit_uv_new';
        self::assertNull($this->data->getType());

        $this->data->setType($type);

        self::assertSame($type, $this->data->getType());
    }

    public function testNameGetterAndSetter(): void
    {
        $name = 'Beijing';
        self::assertNull($this->data->getName());

        $this->data->setName($name);

        self::assertSame($name, $this->data->getName());
    }

    public function testValueGetterAndSetter(): void
    {
        $value = '100';
        self::assertNull($this->data->getValue());

        $this->data->setValue($value);

        self::assertSame($value, $this->data->getValue());
    }

    public function testValueIdGetterAndSetter(): void
    {
        $valueId = '110000';
        self::assertNull($this->data->getValueId());

        $this->data->setValueId($valueId);

        self::assertSame($valueId, $this->data->getValueId());
    }

    public function testAccountGetterAndSetter(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        self::assertNull($this->data->getAccount());

        $this->data->setAccount($account);

        self::assertSame($account, $this->data->getAccount());
    }

    public function testRetrieveAdminArrayReturnsCompleteData(): void
    {
        // Arrange
        $id = '123456789';
        $date = '20230101-20230107';
        $type = 'visit_uv_new';
        $name = 'Beijing';
        $value = '100';
        $valueId = '110000';
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        $createTime = new \DateTimeImmutable();

        // 使用反射设置私有 ID 属性
        $reflection = new \ReflectionProperty(UserPortraitProvinceData::class, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->data, $id);

        $this->data->setDate($date);
        $this->data->setType($type);
        $this->data->setName($name);
        $this->data->setValue($value);
        $this->data->setValueId($valueId);
        $this->data->setAccount($account);
        $this->data->setCreateTime($createTime);

        // Act
        $result = $this->data->retrieveAdminArray();

        // Assert
        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('date', $result);
        self::assertArrayHasKey('type', $result);
        self::assertArrayHasKey('name', $result);
        self::assertArrayHasKey('value', $result);
        self::assertArrayHasKey('valueId', $result);
        self::assertArrayHasKey('account', $result);
        self::assertArrayHasKey('createTime', $result);

        self::assertEquals($id, $result['id']);
        self::assertEquals($date, $result['date']);
        self::assertEquals($type, $result['type']);
        self::assertEquals($name, $result['name']);
        self::assertEquals($value, $result['value']);
        self::assertEquals($valueId, $result['valueId']);
        self::assertEquals($account, $result['account']);
        self::assertEquals($createTime, $result['createTime']);
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;

class UserPortraitProvinceDataTest extends TestCase
{
    private UserPortraitProvinceData $data;

    protected function setUp(): void
    {
        $this->data = new UserPortraitProvinceData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->data->getId());
    }

    public function testCreateTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->data->getCreateTime());

        $result = $this->data->setCreateTime($now);

        $this->assertSame($now, $this->data->getCreateTime());
        $this->assertSame($this->data, $result, 'Setter should return self for method chaining');
    }

    public function testDate_getterAndSetter(): void
    {
        $date = '20230101-20230107';
        $this->assertNull($this->data->getDate());

        $result = $this->data->setDate($date);

        $this->assertSame($date, $this->data->getDate());
        $this->assertSame($this->data, $result, 'Setter should return self for method chaining');
    }

    public function testType_getterAndSetter(): void
    {
        $type = 'visit_uv_new';
        $this->assertNull($this->data->getType());

        $this->data->setType($type);

        $this->assertSame($type, $this->data->getType());
    }

    public function testName_getterAndSetter(): void
    {
        $name = 'Beijing';
        $this->assertNull($this->data->getName());

        $this->data->setName($name);

        $this->assertSame($name, $this->data->getName());
    }

    public function testValue_getterAndSetter(): void
    {
        $value = '100';
        $this->assertNull($this->data->getValue());

        $this->data->setValue($value);

        $this->assertSame($value, $this->data->getValue());
    }

    public function testValueId_getterAndSetter(): void
    {
        $valueId = '110000';
        $this->assertNull($this->data->getValueId());

        $this->data->setValueId($valueId);

        $this->assertSame($valueId, $this->data->getValueId());
    }

    public function testAccount_getterAndSetter(): void
    {
        $account = $this->createMock(Account::class);
        $this->assertNull($this->data->getAccount());

        $result = $this->data->setAccount($account);

        $this->assertSame($account, $this->data->getAccount());
        $this->assertSame($this->data, $result, 'Setter should return self for method chaining');
    }

    public function testRetrieveAdminArray_returnsCompleteData(): void
    {
        // Arrange
        $id = '123456789';
        $date = '20230101-20230107';
        $type = 'visit_uv_new';
        $name = 'Beijing';
        $value = '100';
        $valueId = '110000';
        $account = $this->createMock(Account::class);
        $createTime = new DateTimeImmutable();

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
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('valueId', $result);
        $this->assertArrayHasKey('account', $result);
        $this->assertArrayHasKey('createTime', $result);

        $this->assertEquals($id, $result['id']);
        $this->assertEquals($date, $result['date']);
        $this->assertEquals($type, $result['type']);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($value, $result['value']);
        $this->assertEquals($valueId, $result['valueId']);
        $this->assertEquals($account, $result['account']);
        $this->assertEquals($createTime, $result['createTime']);
    }
}

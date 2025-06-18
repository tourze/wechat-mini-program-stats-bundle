<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;

class UserPortraitDataTest extends TestCase
{
    private UserPortraitData $userPortraitData;

    protected function setUp(): void
    {
        $this->userPortraitData = new UserPortraitData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userPortraitData->getId());
    }

    public function testCreateTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->userPortraitData->getCreateTime());

        $result = $this->userPortraitData->setCreateTime($now);

        $this->assertSame($now, $this->userPortraitData->getCreateTime());
        $this->assertSame($this->userPortraitData, $result, 'Setter should return self for method chaining');
    }

    public function testDate_getterAndSetter(): void
    {
        $date = '20230101';
        $this->assertNull($this->userPortraitData->getDate());

        $result = $this->userPortraitData->setDate($date);

        $this->assertSame($date, $this->userPortraitData->getDate());
        $this->assertSame($this->userPortraitData, $result, 'Setter should return self for method chaining');
    }

    public function testType_getterAndSetter(): void
    {
        $type = 'visit_uv_new';
        $this->assertNull($this->userPortraitData->getType());

        $this->userPortraitData->setType($type);

        $this->assertSame($type, $this->userPortraitData->getType());
    }

    public function testBeginTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->userPortraitData->getBeginTime());

        $result = $this->userPortraitData->setBeginTime($now);

        $this->assertSame($now, $this->userPortraitData->getBeginTime());
        $this->assertSame($this->userPortraitData, $result, 'Setter should return self for method chaining');
    }

    public function testEndTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->userPortraitData->getEndTime());

        $result = $this->userPortraitData->setEndTime($now);

        $this->assertSame($now, $this->userPortraitData->getEndTime());
        $this->assertSame($this->userPortraitData, $result, 'Setter should return self for method chaining');
    }

    public function testUserType_getterAndSetter(): void
    {
        $userType = 'new_user';
        $this->assertNull($this->userPortraitData->getUserType());

        $this->userPortraitData->setUserType($userType);

        $this->assertSame($userType, $this->userPortraitData->getUserType());
    }

    public function testProvince_getterAndSetter(): void
    {
        $province = 'Beijing';

        $this->userPortraitData->setProvince($province);

        $this->assertSame($province, $this->userPortraitData->getProvince());
    }

    public function testName_getterAndSetter(): void
    {
        $name = 'TestName';
        $this->assertNull($this->userPortraitData->getName());

        $this->userPortraitData->setName($name);

        $this->assertSame($name, $this->userPortraitData->getName());
    }

    public function testValue_getterAndSetter(): void
    {
        $value = '100';
        $this->assertNull($this->userPortraitData->getValue());

        $this->userPortraitData->setValue($value);

        $this->assertSame($value, $this->userPortraitData->getValue());
    }

    public function testAccount_getterAndSetter(): void
    {
        $account = $this->createMock(Account::class);
        $this->assertNull($this->userPortraitData->getAccount());

        $result = $this->userPortraitData->setAccount($account);

        $this->assertSame($account, $this->userPortraitData->getAccount());
        $this->assertSame($this->userPortraitData, $result, 'Setter should return self for method chaining');
    }

    public function testRetrieveAdminArray_returnsExpectedFormat(): void
    {
        $date = '20230101';
        $type = 'visit_uv_new';
        $name = 'TestName';
        $value = '100';

        $this->userPortraitData->setDate($date);
        $this->userPortraitData->setType($type);
        $this->userPortraitData->setName($name);
        $this->userPortraitData->setValue($value);

        $result = $this->userPortraitData->retrieveAdminArray();
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('value', $result);
        $this->assertEquals($date, $result['date']);
        $this->assertEquals($type, $result['type']);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($value, $result['value']);
    }
}

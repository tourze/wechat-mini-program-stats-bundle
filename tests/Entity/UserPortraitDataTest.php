<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;

/**
 * @internal
 */
#[CoversClass(UserPortraitData::class)]
final class UserPortraitDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitData();
    }

    public static function propertiesProvider(): iterable
    {
        return [
            'id' => ['id', 'test_id'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
        ];
    }

    private UserPortraitData $userPortraitData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPortraitData = new UserPortraitData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userPortraitData->getId());
    }

    public function testCreateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->userPortraitData->getCreateTime());

        $this->userPortraitData->setCreateTime($now);

        self::assertSame($now, $this->userPortraitData->getCreateTime());
    }

    public function testDateGetterAndSetter(): void
    {
        $date = '20230101';
        self::assertNull($this->userPortraitData->getDate());

        $this->userPortraitData->setDate($date);

        self::assertSame($date, $this->userPortraitData->getDate());
    }

    public function testTypeGetterAndSetter(): void
    {
        $type = 'visit_uv_new';
        self::assertNull($this->userPortraitData->getType());

        $this->userPortraitData->setType($type);

        self::assertSame($type, $this->userPortraitData->getType());
    }

    public function testBeginTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->userPortraitData->getBeginTime());

        $this->userPortraitData->setBeginTime($now);

        self::assertSame($now, $this->userPortraitData->getBeginTime());
    }

    public function testEndTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->userPortraitData->getEndTime());

        $this->userPortraitData->setEndTime($now);

        self::assertSame($now, $this->userPortraitData->getEndTime());
    }

    public function testUserTypeGetterAndSetter(): void
    {
        $userType = 'new_user';
        self::assertNull($this->userPortraitData->getUserType());

        $this->userPortraitData->setUserType($userType);

        self::assertSame($userType, $this->userPortraitData->getUserType());
    }

    public function testProvinceGetterAndSetter(): void
    {
        $province = 'Beijing';

        $this->userPortraitData->setProvince($province);

        self::assertSame($province, $this->userPortraitData->getProvince());
    }

    public function testNameGetterAndSetter(): void
    {
        $name = 'TestName';
        self::assertNull($this->userPortraitData->getName());

        $this->userPortraitData->setName($name);

        self::assertSame($name, $this->userPortraitData->getName());
    }

    public function testValueGetterAndSetter(): void
    {
        $value = '100';
        self::assertNull($this->userPortraitData->getValue());

        $this->userPortraitData->setValue($value);

        self::assertSame($value, $this->userPortraitData->getValue());
    }

    public function testAccountGetterAndSetter(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        self::assertNull($this->userPortraitData->getAccount());

        $this->userPortraitData->setAccount($account);

        self::assertSame($account, $this->userPortraitData->getAccount());
    }

    public function testRetrieveAdminArrayReturnsExpectedFormat(): void
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
        self::assertArrayHasKey('date', $result);
        self::assertArrayHasKey('type', $result);
        self::assertArrayHasKey('name', $result);
        self::assertArrayHasKey('value', $result);
        self::assertEquals($date, $result['date']);
        self::assertEquals($type, $result['type']);
        self::assertEquals($name, $result['name']);
        self::assertEquals($value, $result['value']);
    }
}

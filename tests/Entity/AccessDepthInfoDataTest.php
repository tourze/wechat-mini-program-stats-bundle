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
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        self::assertNull($this->accessDepthInfoData->getAccount());

        $this->accessDepthInfoData->setAccount($account);

        self::assertSame($account, $this->accessDepthInfoData->getAccount());
    }

    public function testRetrieveAdminArrayReturnsExpectedFormat(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');
        $dataKey = 'test_key';
        $dataValue = 'test_value';
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
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

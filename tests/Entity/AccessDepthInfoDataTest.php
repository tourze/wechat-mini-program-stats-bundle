<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;

class AccessDepthInfoDataTest extends TestCase
{
    private AccessDepthInfoData $accessDepthInfoData;

    protected function setUp(): void
    {
        $this->accessDepthInfoData = new AccessDepthInfoData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->accessDepthInfoData->getId());
    }

    public function testCreateTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->accessDepthInfoData->getCreateTime());

        $result = $this->accessDepthInfoData->setCreateTime($now);

        $this->assertSame($now, $this->accessDepthInfoData->getCreateTime());
        $this->assertSame($this->accessDepthInfoData, $result, 'Setter should return self for method chaining');
    }

    public function testDate_getterAndSetter(): void
    {
        $date = new DateTimeImmutable('2023-01-01');
        $this->assertNull($this->accessDepthInfoData->getDate());

        $result = $this->accessDepthInfoData->setDate($date);

        $this->assertSame($date, $this->accessDepthInfoData->getDate());
        $this->assertSame($this->accessDepthInfoData, $result, 'Setter should return self for method chaining');
    }

    public function testDataKey_getterAndSetter(): void
    {
        $dataKey = 'test_key';
        $this->assertNull($this->accessDepthInfoData->getDataKey());

        $this->accessDepthInfoData->setDataKey($dataKey);

        $this->assertSame($dataKey, $this->accessDepthInfoData->getDataKey());
    }

    public function testDataValue_getterAndSetter(): void
    {
        $dataValue = 'test_value';
        $this->assertNull($this->accessDepthInfoData->getDataValue());

        $this->accessDepthInfoData->setDataValue($dataValue);

        $this->assertSame($dataValue, $this->accessDepthInfoData->getDataValue());
    }

    public function testAccount_getterAndSetter(): void
    {
        $account = $this->createMock(Account::class);
        $this->assertNull($this->accessDepthInfoData->getAccount());

        $result = $this->accessDepthInfoData->setAccount($account);

        $this->assertSame($account, $this->accessDepthInfoData->getAccount());
        $this->assertSame($this->accessDepthInfoData, $result, 'Setter should return self for method chaining');
    }

    public function testRetrieveAdminArray_returnsExpectedFormat(): void
    {
        $date = new DateTimeImmutable('2023-01-01');
        $dataKey = 'test_key';
        $dataValue = 'test_value';
        $account = $this->createMock(Account::class);
        $createTime = new DateTimeImmutable();

        $this->accessDepthInfoData->setDate($date);
        $this->accessDepthInfoData->setDataKey($dataKey);
        $this->accessDepthInfoData->setDataValue($dataValue);
        $this->accessDepthInfoData->setAccount($account);
        $this->accessDepthInfoData->setCreateTime($createTime);

        $result = $this->accessDepthInfoData->retrieveAdminArray();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('dataKey', $result);
        $this->assertArrayHasKey('dataValue', $result);
        $this->assertArrayHasKey('account', $result);
        $this->assertArrayHasKey('createTime', $result);
        $this->assertSame($date, $result['date']);
        $this->assertSame($dataKey, $result['dataKey']);
        $this->assertSame($dataValue, $result['dataValue']);
        $this->assertSame($account, $result['account']);
        $this->assertSame($createTime, $result['createTime']);
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->accessDepthInfoData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

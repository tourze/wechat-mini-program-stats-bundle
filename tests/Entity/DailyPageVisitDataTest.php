<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;

class DailyPageVisitDataTest extends TestCase
{
    private DailyPageVisitData $dailyPageVisitData;

    protected function setUp(): void
    {
        $this->dailyPageVisitData = new DailyPageVisitData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertEquals(0, $this->dailyPageVisitData->getId());
    }

    public function testCreateTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->dailyPageVisitData->getCreateTime());

        $result = $this->dailyPageVisitData->setCreateTime($now);

        $this->assertSame($now, $this->dailyPageVisitData->getCreateTime());
        $this->assertSame($this->dailyPageVisitData, $result, 'Setter should return self for method chaining');
    }

    public function testUpdateTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->dailyPageVisitData->getUpdateTime());

        $result = $this->dailyPageVisitData->setUpdateTime($now);

        $this->assertSame($now, $this->dailyPageVisitData->getUpdateTime());
        $this->assertSame($this->dailyPageVisitData, $result, 'Setter should return self for method chaining');
    }

    public function testDate_getterAndSetter(): void
    {
        $date = new DateTimeImmutable('2023-01-01');
        $this->assertNull($this->dailyPageVisitData->getDate());

        $result = $this->dailyPageVisitData->setDate($date);

        $this->assertSame($date, $this->dailyPageVisitData->getDate());
        $this->assertSame($this->dailyPageVisitData, $result, 'Setter should return self for method chaining');
    }

    public function testPage_getterAndSetter(): void
    {
        $page = '/pages/index';

        $result = $this->dailyPageVisitData->setPage($page);

        $this->assertSame($page, $this->dailyPageVisitData->getPage());
        $this->assertSame($this->dailyPageVisitData, $result, 'Setter should return self for method chaining');
    }

    public function testVisitPv_getterAndSetter(): void
    {
        $visitPv = 100;

        $result = $this->dailyPageVisitData->setVisitPv($visitPv);

        $this->assertSame($visitPv, $this->dailyPageVisitData->getVisitPv());
        $this->assertSame($this->dailyPageVisitData, $result, 'Setter should return self for method chaining');
    }

    public function testVisitUv_getterAndSetter(): void
    {
        $visitUv = 50;

        $this->dailyPageVisitData->setVisitUv($visitUv);

        $this->assertSame($visitUv, $this->dailyPageVisitData->getVisitUv());
    }

    public function testNewUserVisitPv_getterAndSetter(): void
    {
        $newUserVisitPv = 30;

        $this->dailyPageVisitData->setNewUserVisitPv($newUserVisitPv);

        $this->assertSame($newUserVisitPv, $this->dailyPageVisitData->getNewUserVisitPv());
    }

    public function testNewUserVisitUv_getterAndSetter(): void
    {
        $newUserVisitUv = 20;

        $this->dailyPageVisitData->setNewUserVisitUv($newUserVisitUv);

        $this->assertSame($newUserVisitUv, $this->dailyPageVisitData->getNewUserVisitUv());
    }

    public function testRetrieveAdminArray_returnsExpectedFormat(): void
    {
        $date = new DateTimeImmutable('2023-01-01');
        $page = '/pages/index';
        $visitPv = 100;
        $visitUv = 50;
        $newUserVisitPv = 30;
        $newUserVisitUv = 20;
        $createTime = new DateTimeImmutable();
        $updateTime = new DateTimeImmutable();

        $this->dailyPageVisitData->setDate($date);
        $this->dailyPageVisitData->setPage($page);
        $this->dailyPageVisitData->setVisitPv($visitPv);
        $this->dailyPageVisitData->setVisitUv($visitUv);
        $this->dailyPageVisitData->setNewUserVisitPv($newUserVisitPv);
        $this->dailyPageVisitData->setNewUserVisitUv($newUserVisitUv);
        $this->dailyPageVisitData->setCreateTime($createTime);
        $this->dailyPageVisitData->setUpdateTime($updateTime);

        $result = $this->dailyPageVisitData->retrieveAdminArray();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('visitPv', $result);
        $this->assertArrayHasKey('visitUv', $result);
        $this->assertArrayHasKey('newUserVisitPv', $result);
        $this->assertArrayHasKey('newUserVisitUv', $result);
        $this->assertArrayHasKey('createTime', $result);
        $this->assertArrayHasKey('updateTime', $result);
        $this->assertSame($date, $result['date']);
        $this->assertSame($page, $result['page']);
        $this->assertSame($visitPv, $result['visitPv']);
        $this->assertSame($visitUv, $result['visitUv']);
        $this->assertSame($newUserVisitPv, $result['newUserVisitPv']);
        $this->assertSame($newUserVisitUv, $result['newUserVisitUv']);
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->dailyPageVisitData->__toString();
        $this->assertSame('0', $result); // ID is 0 initially
    }
}

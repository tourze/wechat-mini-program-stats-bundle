<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;

class DailyNewUserVisitPvTest extends TestCase
{
    private DailyNewUserVisitPv $dailyNewUserVisitPv;

    protected function setUp(): void
    {
        $this->dailyNewUserVisitPv = new DailyNewUserVisitPv();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->dailyNewUserVisitPv->getId());
    }

    public function testCreateTime_getterAndSetter(): void
    {
        $now = new DateTimeImmutable();
        $this->assertNull($this->dailyNewUserVisitPv->getCreateTime());

        $result = $this->dailyNewUserVisitPv->setCreateTime($now);

        $this->assertSame($now, $this->dailyNewUserVisitPv->getCreateTime());
        $this->assertSame($this->dailyNewUserVisitPv, $result, 'Setter should return self for method chaining');
    }

    public function testDate_getterAndSetter(): void
    {
        $date = new DateTimeImmutable('2023-01-01');

        $this->dailyNewUserVisitPv->setDate($date);

        $this->assertSame($date, $this->dailyNewUserVisitPv->getDate());
    }

    public function testVisitPv_getterAndSetter(): void
    {
        $visitPv = 100;
        $this->assertEquals(0, $this->dailyNewUserVisitPv->getVisitPv());

        $this->dailyNewUserVisitPv->setVisitPv($visitPv);

        $this->assertSame($visitPv, $this->dailyNewUserVisitPv->getVisitPv());
    }

    public function testVisitUv_getterAndSetter(): void
    {
        $visitUv = 50;
        $this->assertEquals(0, $this->dailyNewUserVisitPv->getVisitUv());

        $result = $this->dailyNewUserVisitPv->setVisitUv($visitUv);

        $this->assertSame($visitUv, $this->dailyNewUserVisitPv->getVisitUv());
        $this->assertSame($this->dailyNewUserVisitPv, $result, 'Setter should return self for method chaining');
    }

    public function testRemark_getterAndSetter(): void
    {
        $remark = 'Test remark';
        $this->assertNull($this->dailyNewUserVisitPv->getRemark());

        $result = $this->dailyNewUserVisitPv->setRemark($remark);

        $this->assertSame($remark, $this->dailyNewUserVisitPv->getRemark());
        $this->assertSame($this->dailyNewUserVisitPv, $result, 'Setter should return self for method chaining');
    }

    public function testAccount_getterAndSetter(): void
    {
        $account = $this->createMock(Account::class);
        $this->assertNull($this->dailyNewUserVisitPv->getAccount());

        $result = $this->dailyNewUserVisitPv->setAccount($account);

        $this->assertSame($account, $this->dailyNewUserVisitPv->getAccount());
        $this->assertSame($this->dailyNewUserVisitPv, $result, 'Setter should return self for method chaining');
    }

    public function testRetrieveAdminArray_returnsExpectedFormat(): void
    {
        $date = new DateTimeImmutable('2023-01-01');
        $visitPv = 100;
        $visitUv = 50;
        $createTime = new DateTimeImmutable();

        $this->dailyNewUserVisitPv->setDate($date);
        $this->dailyNewUserVisitPv->setVisitPv($visitPv);
        $this->dailyNewUserVisitPv->setVisitUv($visitUv);
        $this->dailyNewUserVisitPv->setCreateTime($createTime);

        $result = $this->dailyNewUserVisitPv->retrieveAdminArray();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('visitPv', $result);
        $this->assertArrayHasKey('visitUv', $result);
        $this->assertArrayHasKey('createTime', $result);
        $this->assertSame($date, $result['date']);
        $this->assertSame($visitPv, $result['visitPv']);
        $this->assertSame($visitUv, $result['visitUv']);
        $this->assertSame($createTime, $result['createTime']);
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->dailyNewUserVisitPv->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
} 
<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;

/**
 * @internal
 */
#[CoversClass(DailyNewUserVisitPv::class)]
final class DailyNewUserVisitPvTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new DailyNewUserVisitPv();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'visitUv' => ['visitUv', 123],
        ];
    }

    private DailyNewUserVisitPv $dailyNewUserVisitPv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dailyNewUserVisitPv = new DailyNewUserVisitPv();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->dailyNewUserVisitPv->getId());
    }

    public function testCreateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->dailyNewUserVisitPv->getCreateTime());

        $this->dailyNewUserVisitPv->setCreateTime($now);

        self::assertSame($now, $this->dailyNewUserVisitPv->getCreateTime());
    }

    public function testDateGetterAndSetter(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');

        $this->dailyNewUserVisitPv->setDate($date);

        self::assertSame($date, $this->dailyNewUserVisitPv->getDate());
    }

    public function testVisitPvGetterAndSetter(): void
    {
        $visitPv = 100;
        self::assertEquals(0, $this->dailyNewUserVisitPv->getVisitPv());

        $this->dailyNewUserVisitPv->setVisitPv($visitPv);

        self::assertSame($visitPv, $this->dailyNewUserVisitPv->getVisitPv());
    }

    public function testVisitUvGetterAndSetter(): void
    {
        $visitUv = 50;
        self::assertEquals(0, $this->dailyNewUserVisitPv->getVisitUv());

        $this->dailyNewUserVisitPv->setVisitUv($visitUv);

        self::assertSame($visitUv, $this->dailyNewUserVisitPv->getVisitUv());
    }

    public function testRemarkGetterAndSetter(): void
    {
        $remark = 'Test remark';
        self::assertNull($this->dailyNewUserVisitPv->getRemark());

        $this->dailyNewUserVisitPv->setRemark($remark);

        self::assertSame($remark, $this->dailyNewUserVisitPv->getRemark());
    }

    public function testAccountGetterAndSetter(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        self::assertNull($this->dailyNewUserVisitPv->getAccount());

        $this->dailyNewUserVisitPv->setAccount($account);

        self::assertSame($account, $this->dailyNewUserVisitPv->getAccount());
    }

    public function testRetrieveAdminArrayReturnsExpectedFormat(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');
        $visitPv = 100;
        $visitUv = 50;
        $createTime = new \DateTimeImmutable();

        $this->dailyNewUserVisitPv->setDate($date);
        $this->dailyNewUserVisitPv->setVisitPv($visitPv);
        $this->dailyNewUserVisitPv->setVisitUv($visitUv);
        $this->dailyNewUserVisitPv->setCreateTime($createTime);

        $result = $this->dailyNewUserVisitPv->retrieveAdminArray();

        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('date', $result);
        self::assertArrayHasKey('visitPv', $result);
        self::assertArrayHasKey('visitUv', $result);
        self::assertArrayHasKey('createTime', $result);
        self::assertSame($date, $result['date']);
        self::assertSame($visitPv, $result['visitPv']);
        self::assertSame($visitUv, $result['visitUv']);
        self::assertSame($createTime, $result['createTime']);
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->dailyNewUserVisitPv->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

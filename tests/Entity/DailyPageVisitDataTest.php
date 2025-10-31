<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;

/**
 * @internal
 */
#[CoversClass(DailyPageVisitData::class)]
final class DailyPageVisitDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new DailyPageVisitData();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'page' => ['page', 'test_value'],
            'visitPv' => ['visitPv', 123],
        ];
    }

    private DailyPageVisitData $dailyPageVisitData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dailyPageVisitData = new DailyPageVisitData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertEquals(0, $this->dailyPageVisitData->getId());
    }

    public function testCreateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->dailyPageVisitData->getCreateTime());

        $this->dailyPageVisitData->setCreateTime($now);

        self::assertSame($now, $this->dailyPageVisitData->getCreateTime());
    }

    public function testUpdateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        self::assertNull($this->dailyPageVisitData->getUpdateTime());

        $this->dailyPageVisitData->setUpdateTime($now);

        self::assertSame($now, $this->dailyPageVisitData->getUpdateTime());
    }

    public function testDateGetterAndSetter(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');
        self::assertNull($this->dailyPageVisitData->getDate());

        $this->dailyPageVisitData->setDate($date);

        self::assertSame($date, $this->dailyPageVisitData->getDate());
    }

    public function testPageGetterAndSetter(): void
    {
        $page = '/pages/index';

        $this->dailyPageVisitData->setPage($page);

        self::assertSame($page, $this->dailyPageVisitData->getPage());
    }

    public function testVisitPvGetterAndSetter(): void
    {
        $visitPv = 100;

        $this->dailyPageVisitData->setVisitPv($visitPv);

        self::assertSame($visitPv, $this->dailyPageVisitData->getVisitPv());
    }

    public function testVisitUvGetterAndSetter(): void
    {
        $visitUv = 50;

        $this->dailyPageVisitData->setVisitUv($visitUv);

        self::assertSame($visitUv, $this->dailyPageVisitData->getVisitUv());
    }

    public function testNewUserVisitPvGetterAndSetter(): void
    {
        $newUserVisitPv = 30;

        $this->dailyPageVisitData->setNewUserVisitPv($newUserVisitPv);

        self::assertSame($newUserVisitPv, $this->dailyPageVisitData->getNewUserVisitPv());
    }

    public function testNewUserVisitUvGetterAndSetter(): void
    {
        $newUserVisitUv = 20;

        $this->dailyPageVisitData->setNewUserVisitUv($newUserVisitUv);

        self::assertSame($newUserVisitUv, $this->dailyPageVisitData->getNewUserVisitUv());
    }

    public function testRetrieveAdminArrayReturnsExpectedFormat(): void
    {
        $date = new \DateTimeImmutable('2023-01-01');
        $page = '/pages/index';
        $visitPv = 100;
        $visitUv = 50;
        $newUserVisitPv = 30;
        $newUserVisitUv = 20;
        $createTime = new \DateTimeImmutable();
        $updateTime = new \DateTimeImmutable();

        $this->dailyPageVisitData->setDate($date);
        $this->dailyPageVisitData->setPage($page);
        $this->dailyPageVisitData->setVisitPv($visitPv);
        $this->dailyPageVisitData->setVisitUv($visitUv);
        $this->dailyPageVisitData->setNewUserVisitPv($newUserVisitPv);
        $this->dailyPageVisitData->setNewUserVisitUv($newUserVisitUv);
        $this->dailyPageVisitData->setCreateTime($createTime);
        $this->dailyPageVisitData->setUpdateTime($updateTime);

        $result = $this->dailyPageVisitData->retrieveAdminArray();

        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('date', $result);
        self::assertArrayHasKey('page', $result);
        self::assertArrayHasKey('visitPv', $result);
        self::assertArrayHasKey('visitUv', $result);
        self::assertArrayHasKey('newUserVisitPv', $result);
        self::assertArrayHasKey('newUserVisitUv', $result);
        self::assertArrayHasKey('createTime', $result);
        self::assertArrayHasKey('updateTime', $result);
        self::assertSame($date, $result['date']);
        self::assertSame($page, $result['page']);
        self::assertSame($visitPv, $result['visitPv']);
        self::assertSame($visitUv, $result['visitUv']);
        self::assertSame($newUserVisitPv, $result['newUserVisitPv']);
        self::assertSame($newUserVisitUv, $result['newUserVisitUv']);
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->dailyPageVisitData->__toString();
        self::assertSame('0', $result); // ID is 0 initially
    }
}

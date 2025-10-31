<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;

/**
 * @internal
 */
#[CoversClass(DailyVisitTrendData::class)]
final class DailyVisitTrendDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new DailyVisitTrendData();
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

    private DailyVisitTrendData $dailyVisitTrendData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dailyVisitTrendData = new DailyVisitTrendData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->dailyVisitTrendData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->dailyVisitTrendData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

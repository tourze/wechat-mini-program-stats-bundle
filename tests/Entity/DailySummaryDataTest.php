<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;

/**
 * @internal
 */
#[CoversClass(DailySummaryData::class)]
final class DailySummaryDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new DailySummaryData();
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

    private DailySummaryData $dailySummaryData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dailySummaryData = new DailySummaryData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->dailySummaryData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->dailySummaryData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

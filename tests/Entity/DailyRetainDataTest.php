<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;

/**
 * @internal
 */
#[CoversClass(DailyRetainData::class)]
final class DailyRetainDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new DailyRetainData();
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

    private DailyRetainData $dailyRetainData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dailyRetainData = new DailyRetainData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->dailyRetainData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->dailyRetainData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\PerformanceData;

/**
 * @internal
 */
#[CoversClass(PerformanceData::class)]
final class PerformanceDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new PerformanceData();
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

    private PerformanceData $performanceData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->performanceData = new PerformanceData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->performanceData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->performanceData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

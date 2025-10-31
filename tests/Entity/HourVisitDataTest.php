<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;

/**
 * @internal
 */
#[CoversClass(HourVisitData::class)]
final class HourVisitDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new HourVisitData();
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

    private HourVisitData $hourVisitData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hourVisitData = new HourVisitData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->hourVisitData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->hourVisitData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

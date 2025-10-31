<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;

/**
 * @internal
 */
#[CoversClass(PerformanceAttribute::class)]
final class PerformanceAttributeTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new PerformanceAttribute();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'name' => ['name', 'test_name'],
            'value' => ['value', 'test_value'],
        ];
    }

    private PerformanceAttribute $performanceAttribute;

    protected function setUp(): void
    {
        parent::setUp();

        $this->performanceAttribute = new PerformanceAttribute();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertEquals(0, $this->performanceAttribute->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->performanceAttribute->__toString();
        self::assertSame('0', $result); // ID is 0 initially
    }
}

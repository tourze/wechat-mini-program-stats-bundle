<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\Performance;

/**
 * @internal
 */
#[CoversClass(Performance::class)]
final class PerformanceTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new Performance();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'name' => ['name', 'test_name'],
            'nameZh' => ['nameZh', 'test_name_zh'],
        ];
    }

    private Performance $performance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->performance = new Performance();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertEquals(0, $this->performance->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->performance->__toString();
        self::assertSame('0', $result); // ID is 0 initially
    }
}

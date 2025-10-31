<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;

/**
 * @internal
 */
#[CoversClass(OperationPerformance::class)]
final class OperationPerformanceTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new OperationPerformance();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'costTimeType' => ['costTimeType', 'test_type'],
            'costTime' => ['costTime', 'test_time'],
        ];
    }

    private OperationPerformance $operationPerformance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->operationPerformance = new OperationPerformance();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertEquals(0, $this->operationPerformance->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->operationPerformance->__toString();
        self::assertSame('0', $result); // ID is 0 initially
    }
}

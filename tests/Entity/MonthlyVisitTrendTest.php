<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;

/**
 * @internal
 */
#[CoversClass(MonthlyVisitTrend::class)]
final class MonthlyVisitTrendTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new MonthlyVisitTrend();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'sessionCnt' => ['sessionCnt', 'test_session_cnt'],
            'visitPv' => ['visitPv', 'test_visit_pv'],
            'visitUv' => ['visitUv', 'test_visit_uv'],
        ];
    }

    private MonthlyVisitTrend $monthlyVisitTrend;

    protected function setUp(): void
    {
        parent::setUp();

        $this->monthlyVisitTrend = new MonthlyVisitTrend();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertEquals(0, $this->monthlyVisitTrend->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->monthlyVisitTrend->__toString();
        self::assertSame('0', $result); // ID is 0 initially
    }
}

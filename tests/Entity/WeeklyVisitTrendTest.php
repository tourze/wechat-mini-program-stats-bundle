<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;

/**
 * @internal
 */
#[CoversClass(WeeklyVisitTrend::class)]
final class WeeklyVisitTrendTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new WeeklyVisitTrend();
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

    private WeeklyVisitTrend $weeklyVisitTrend;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weeklyVisitTrend = new WeeklyVisitTrend();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertEquals(0, $this->weeklyVisitTrend->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->weeklyVisitTrend->__toString();
        self::assertSame('0', $result); // ID is 0 initially
    }
}

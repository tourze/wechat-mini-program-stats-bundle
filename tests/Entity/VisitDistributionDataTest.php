<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\VisitDistributionData;

/**
 * @internal
 */
#[CoversClass(VisitDistributionData::class)]
final class VisitDistributionDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new VisitDistributionData();
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

    private VisitDistributionData $visitDistributionData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->visitDistributionData = new VisitDistributionData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->visitDistributionData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->visitDistributionData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

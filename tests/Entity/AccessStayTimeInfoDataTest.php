<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;

/**
 * @internal
 */
#[CoversClass(AccessStayTimeInfoData::class)]
final class AccessStayTimeInfoDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new AccessStayTimeInfoData();
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

    private AccessStayTimeInfoData $accessStayTimeInfoData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessStayTimeInfoData = new AccessStayTimeInfoData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->accessStayTimeInfoData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->accessStayTimeInfoData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

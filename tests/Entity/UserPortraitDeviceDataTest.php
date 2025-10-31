<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;

/**
 * @internal
 */
#[CoversClass(UserPortraitDeviceData::class)]
final class UserPortraitDeviceDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitDeviceData();
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

    private UserPortraitDeviceData $userPortraitDeviceData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPortraitDeviceData = new UserPortraitDeviceData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userPortraitDeviceData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userPortraitDeviceData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

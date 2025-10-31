<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;

/**
 * @internal
 */
#[CoversClass(UserPortraitPlatformData::class)]
final class UserPortraitPlatformDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitPlatformData();
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

    private UserPortraitPlatformData $userPortraitPlatformData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPortraitPlatformData = new UserPortraitPlatformData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userPortraitPlatformData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userPortraitPlatformData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

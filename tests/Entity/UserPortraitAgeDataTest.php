<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;

/**
 * @internal
 */
#[CoversClass(UserPortraitAgeData::class)]
final class UserPortraitAgeDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitAgeData();
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

    private UserPortraitAgeData $userPortraitAgeData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPortraitAgeData = new UserPortraitAgeData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userPortraitAgeData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userPortraitAgeData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

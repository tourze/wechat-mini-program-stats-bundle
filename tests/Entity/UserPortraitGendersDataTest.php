<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;

/**
 * @internal
 */
#[CoversClass(UserPortraitGendersData::class)]
final class UserPortraitGendersDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitGendersData();
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

    private UserPortraitGendersData $userPortraitGendersData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPortraitGendersData = new UserPortraitGendersData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userPortraitGendersData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userPortraitGendersData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

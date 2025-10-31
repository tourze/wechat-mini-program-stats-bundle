<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;

/**
 * @internal
 */
#[CoversClass(UserPortraitCityData::class)]
final class UserPortraitCityDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserPortraitCityData();
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

    private UserPortraitCityData $userPortraitCityData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPortraitCityData = new UserPortraitCityData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userPortraitCityData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userPortraitCityData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

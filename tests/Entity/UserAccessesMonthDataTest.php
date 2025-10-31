<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;

/**
 * @internal
 */
#[CoversClass(UserAccessesMonthData::class)]
final class UserAccessesMonthDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserAccessesMonthData();
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

    private UserAccessesMonthData $userAccessesMonthData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccessesMonthData = new UserAccessesMonthData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userAccessesMonthData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userAccessesMonthData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;

/**
 * @internal
 */
#[CoversClass(UserAccessesWeekData::class)]
final class UserAccessesWeekDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserAccessesWeekData();
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

    private UserAccessesWeekData $userAccessesWeekData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccessesWeekData = new UserAccessesWeekData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userAccessesWeekData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userAccessesWeekData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

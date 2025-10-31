<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;

/**
 * @internal
 */
#[CoversClass(UserAccessPageData::class)]
final class UserAccessPageDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new UserAccessPageData();
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

    private UserAccessPageData $userAccessPageData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccessPageData = new UserAccessPageData();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->userAccessPageData->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->userAccessPageData->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

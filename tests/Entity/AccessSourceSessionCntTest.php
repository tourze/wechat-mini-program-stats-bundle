<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;

/**
 * @internal
 */
#[CoversClass(AccessSourceSessionCnt::class)]
final class AccessSourceSessionCntTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new AccessSourceSessionCnt();
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

    private AccessSourceSessionCnt $accessSourceSessionCnt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessSourceSessionCnt = new AccessSourceSessionCnt();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->accessSourceSessionCnt->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->accessSourceSessionCnt->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

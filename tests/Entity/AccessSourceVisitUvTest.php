<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;

/**
 * @internal
 */
#[CoversClass(AccessSourceVisitUv::class)]
final class AccessSourceVisitUvTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new AccessSourceVisitUv();
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

    private AccessSourceVisitUv $accessSourceVisitUv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessSourceVisitUv = new AccessSourceVisitUv();
    }

    public function testIdInitiallyNull(): void
    {
        self::assertNull($this->accessSourceVisitUv->getId());
    }

    public function testToStringReturnsIdAsString(): void
    {
        $result = $this->accessSourceVisitUv->__toString();
        self::assertSame('', $result); // ID is null initially
    }
}

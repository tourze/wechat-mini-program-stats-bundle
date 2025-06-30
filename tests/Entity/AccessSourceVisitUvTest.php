<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;

class AccessSourceVisitUvTest extends TestCase
{
    private AccessSourceVisitUv $accessSourceVisitUv;

    protected function setUp(): void
    {
        $this->accessSourceVisitUv = new AccessSourceVisitUv();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->accessSourceVisitUv->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->accessSourceVisitUv->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

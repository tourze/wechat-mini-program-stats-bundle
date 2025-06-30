<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;

class AccessSourceSessionCntTest extends TestCase
{
    private AccessSourceSessionCnt $accessSourceSessionCnt;

    protected function setUp(): void
    {
        $this->accessSourceSessionCnt = new AccessSourceSessionCnt();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->accessSourceSessionCnt->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->accessSourceSessionCnt->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;

class AccessStayTimeInfoDataTest extends TestCase
{
    private AccessStayTimeInfoData $accessStayTimeInfoData;

    protected function setUp(): void
    {
        $this->accessStayTimeInfoData = new AccessStayTimeInfoData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->accessStayTimeInfoData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->accessStayTimeInfoData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

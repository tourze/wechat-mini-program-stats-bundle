<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitPlatformData;

class UserPortraitPlatformDataTest extends TestCase
{
    private UserPortraitPlatformData $userPortraitPlatformData;

    protected function setUp(): void
    {
        $this->userPortraitPlatformData = new UserPortraitPlatformData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userPortraitPlatformData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userPortraitPlatformData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

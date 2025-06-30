<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitDeviceData;

class UserPortraitDeviceDataTest extends TestCase
{
    private UserPortraitDeviceData $userPortraitDeviceData;

    protected function setUp(): void
    {
        $this->userPortraitDeviceData = new UserPortraitDeviceData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userPortraitDeviceData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userPortraitDeviceData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

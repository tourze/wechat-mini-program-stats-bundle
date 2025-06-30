<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;

class UserPortraitAgeDataTest extends TestCase
{
    private UserPortraitAgeData $userPortraitAgeData;

    protected function setUp(): void
    {
        $this->userPortraitAgeData = new UserPortraitAgeData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userPortraitAgeData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userPortraitAgeData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;

class UserPortraitGendersDataTest extends TestCase
{
    private UserPortraitGendersData $userPortraitGendersData;

    protected function setUp(): void
    {
        $this->userPortraitGendersData = new UserPortraitGendersData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userPortraitGendersData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userPortraitGendersData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

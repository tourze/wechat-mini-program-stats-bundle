<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;

class UserPortraitCityDataTest extends TestCase
{
    private UserPortraitCityData $userPortraitCityData;

    protected function setUp(): void
    {
        $this->userPortraitCityData = new UserPortraitCityData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userPortraitCityData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userPortraitCityData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

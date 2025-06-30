<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;

class UserAccessesWeekDataTest extends TestCase
{
    private UserAccessesWeekData $userAccessesWeekData;

    protected function setUp(): void
    {
        $this->userAccessesWeekData = new UserAccessesWeekData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userAccessesWeekData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userAccessesWeekData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

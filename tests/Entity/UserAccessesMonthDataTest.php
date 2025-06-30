<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;

class UserAccessesMonthDataTest extends TestCase
{
    private UserAccessesMonthData $userAccessesMonthData;

    protected function setUp(): void
    {
        $this->userAccessesMonthData = new UserAccessesMonthData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userAccessesMonthData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userAccessesMonthData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

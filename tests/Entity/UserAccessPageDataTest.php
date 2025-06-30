<?php

namespace WechatMiniProgramStatsBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;

class UserAccessPageDataTest extends TestCase
{
    private UserAccessPageData $userAccessPageData;

    protected function setUp(): void
    {
        $this->userAccessPageData = new UserAccessPageData();
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->userAccessPageData->getId());
    }

    public function testToString_returnsIdAsString(): void
    {
        $result = $this->userAccessPageData->__toString();
        $this->assertSame('', $result); // ID is null initially
    }
}

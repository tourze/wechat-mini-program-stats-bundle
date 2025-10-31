<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Interface;

use WechatMiniProgramBundle\Entity\Account;

interface UserPortraitEntityInterface
{
    public function setAccount(?Account $account): void;

    public function setType(?string $type): void;

    public function setDate(?string $date): void;

    public function setName(?string $name): void;

    public function setValueId(?string $valueId): void;

    public function setValue(?string $value): void;
}

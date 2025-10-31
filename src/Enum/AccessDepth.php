<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 用户访问小程序深度
 */
enum AccessDepth: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case TYPE_1 = 1;
    case TYPE_2 = 2;
    case TYPE_3 = 3;
    case TYPE_4 = 4;
    case TYPE_5 = 5;
    case TYPE_6 = 6;
    case TYPE_7 = 7;

    public function getLabel(): string
    {
        return match ($this) {
            self::TYPE_1 => '1 页',
            self::TYPE_2 => '2 页',
            self::TYPE_3 => '3 页',
            self::TYPE_4 => '4 页',
            self::TYPE_5 => '5 页',
            self::TYPE_6 => '6-10 页',
            self::TYPE_7 => '>10 页',
        };
    }
}

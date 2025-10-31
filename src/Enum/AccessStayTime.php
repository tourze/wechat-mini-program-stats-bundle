<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 用户停留在当前页面的时间
 */
enum AccessStayTime: int implements Labelable, Itemable, Selectable
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
    case TYPE_8 = 8;

    public function getLabel(): string
    {
        return match ($this) {
            self::TYPE_1 => '0-2s',
            self::TYPE_2 => '3-5s',
            self::TYPE_3 => '6-10s',
            self::TYPE_4 => '11-20s',
            self::TYPE_5 => '20-30s',
            self::TYPE_6 => '30-50s',
            self::TYPE_7 => '50-100s',
            self::TYPE_8 => '>100s',
        };
    }
}

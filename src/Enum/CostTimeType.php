<?php

namespace WechatMiniProgramStatsBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CostTimeType: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case Launch = 1;
    case Download = 2;
    case Render = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Launch => '启动总耗时',
            self::Download => '下载耗时',
            self::Render => '初次渲染耗时',
        };
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 小程序性能数据类型
 */
enum PerformanceModule: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case TYPE_16 = '10016';
    case TYPE_17 = '10017';
    case TYPE_21 = '10021';
    case TYPE_22 = '10022';
    case TYPE_23 = '10023';

    public function getLabel(): string
    {
        return match ($this) {
            self::TYPE_16 => '打开率',
            self::TYPE_17 => '启动各阶段耗时',
            self::TYPE_21 => '页面切换耗时',
            self::TYPE_22 => '内存指标',
            self::TYPE_23 => '内存异常',
        };
    }
}

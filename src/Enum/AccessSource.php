<?php

namespace WechatMiniProgramStatsBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 当前页
 */
enum AccessSource: int implements Labelable, Itemable, Selectable
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
    case TYPE_9 = 9;
    case TYPE_10 = 10;
    case TYPE_11 = 11;
    case TYPE_12 = 12;
    case TYPE_13 = 13;
    case TYPE_14 = 14;
    case TYPE_15 = 15;
    case TYPE_16 = 16;
    case TYPE_17 = 17;
    case TYPE_18 = 18;
    case TYPE_19 = 19;
    case TYPE_20 = 20;
    case TYPE_21 = 21;
    case TYPE_22 = 22;
    case TYPE_23 = 23;
    case TYPE_24 = 24;
    case TYPE_25 = 25;
    case TYPE_26 = 26;
    case TYPE_27 = 27;
    case TYPE_28 = 28;
    case TYPE_29 = 29;
    case TYPE_30 = 30;
    case TYPE_31 = 31;
    case TYPE_32 = 32;
    case TYPE_33 = 33;
    case TYPE_34 = 34;
    case TYPE_35 = 35;
    case TYPE_36 = 36;

    public function getLabel(): string
    {
        return match ($this) {
            self::TYPE_1 => '小程序历史列表',
            self::TYPE_2 => '搜索',
            self::TYPE_3 => '会话',
            self::TYPE_4 => '扫一扫二维码',
            self::TYPE_5 => '公众号主页',
            self::TYPE_6 => '聊天顶部',
            self::TYPE_7 => '系统桌面',
            self::TYPE_8 => '小程序主页',
            self::TYPE_9 => '附近的小程序',
            self::TYPE_10 => '其他',
            self::TYPE_11 => '模板消息',
            self::TYPE_12 => '未知来源',
            self::TYPE_13 => '公众号菜单',
            self::TYPE_14 => 'APP分享',
            self::TYPE_15 => '支付完成页',
            self::TYPE_16 => '长按识别二维码',
            self::TYPE_17 => '相册选取二维码',
            self::TYPE_18 => '公众号文章	',
            self::TYPE_19 => '钱包',
            self::TYPE_20 => '卡包',
            self::TYPE_21 => '小程序内卡券',
            self::TYPE_22 => '其他小程序',
            self::TYPE_23 => '其他小程序返回',
            self::TYPE_24 => '卡券适用门店列表',
            self::TYPE_25 => '搜索框快捷入口',
            self::TYPE_26 => '小程序客服消息',
            self::TYPE_27 => '公众号下发',
            self::TYPE_28 => '未知来源',
            self::TYPE_29 => '任务栏-最近使用',
            self::TYPE_30 => '长按小程序菜单圆点',
            self::TYPE_31 => '连wifi成功页',
            self::TYPE_32 => '城市服务',
            self::TYPE_33 => '微信广告',
            self::TYPE_34 => '其他移动应用',
            self::TYPE_35 => '发现入口-我的小程序',
            self::TYPE_36 => '任务栏-我的小程序',
        };
    }
}

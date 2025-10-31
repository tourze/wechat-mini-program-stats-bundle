<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramStatsBundle\Enum\AccessSource;

/**
 * @internal
 */
#[CoversClass(AccessSource::class)]
final class AccessSourceTest extends AbstractEnumTestCase
{
    public function testEnumCases(): void
    {
        $cases = AccessSource::cases();

        self::assertCount(36, $cases);
        self::assertContains(AccessSource::TYPE_1, $cases);
        self::assertContains(AccessSource::TYPE_36, $cases);
    }

    #[TestWith([AccessSource::TYPE_1, 1, '小程序历史列表'])]
    #[TestWith([AccessSource::TYPE_2, 2, '搜索'])]
    #[TestWith([AccessSource::TYPE_3, 3, '会话'])]
    #[TestWith([AccessSource::TYPE_4, 4, '扫一扫二维码'])]
    #[TestWith([AccessSource::TYPE_5, 5, '公众号主页'])]
    #[TestWith([AccessSource::TYPE_6, 6, '聊天顶部'])]
    #[TestWith([AccessSource::TYPE_7, 7, '系统桌面'])]
    #[TestWith([AccessSource::TYPE_8, 8, '小程序主页'])]
    #[TestWith([AccessSource::TYPE_9, 9, '附近的小程序'])]
    #[TestWith([AccessSource::TYPE_10, 10, '其他'])]
    #[TestWith([AccessSource::TYPE_11, 11, '模板消息'])]
    #[TestWith([AccessSource::TYPE_12, 12, '未知来源'])]
    #[TestWith([AccessSource::TYPE_13, 13, '公众号菜单'])]
    #[TestWith([AccessSource::TYPE_14, 14, 'APP分享'])]
    #[TestWith([AccessSource::TYPE_15, 15, '支付完成页'])]
    #[TestWith([AccessSource::TYPE_16, 16, '长按识别二维码'])]
    #[TestWith([AccessSource::TYPE_17, 17, '相册选取二维码'])]
    #[TestWith([AccessSource::TYPE_18, 18, '公众号文章	'])]
    #[TestWith([AccessSource::TYPE_19, 19, '钱包'])]
    #[TestWith([AccessSource::TYPE_20, 20, '卡包'])]
    #[TestWith([AccessSource::TYPE_21, 21, '小程序内卡券'])]
    #[TestWith([AccessSource::TYPE_22, 22, '其他小程序'])]
    #[TestWith([AccessSource::TYPE_23, 23, '其他小程序返回'])]
    #[TestWith([AccessSource::TYPE_24, 24, '卡券适用门店列表'])]
    #[TestWith([AccessSource::TYPE_25, 25, '搜索框快捷入口'])]
    #[TestWith([AccessSource::TYPE_26, 26, '小程序客服消息'])]
    #[TestWith([AccessSource::TYPE_27, 27, '公众号下发'])]
    #[TestWith([AccessSource::TYPE_28, 28, '未知来源28'])]
    #[TestWith([AccessSource::TYPE_29, 29, '任务栏-最近使用'])]
    #[TestWith([AccessSource::TYPE_30, 30, '长按小程序菜单圆点'])]
    #[TestWith([AccessSource::TYPE_31, 31, '连wifi成功页'])]
    #[TestWith([AccessSource::TYPE_32, 32, '城市服务'])]
    #[TestWith([AccessSource::TYPE_33, 33, '微信广告'])]
    #[TestWith([AccessSource::TYPE_34, 34, '其他移动应用'])]
    #[TestWith([AccessSource::TYPE_35, 35, '发现入口-我的小程序'])]
    #[TestWith([AccessSource::TYPE_36, 36, '任务栏-我的小程序'])]
    public function testValueAndLabel(AccessSource $enum, int $expectedValue, string $expectedLabel): void
    {
        self::assertSame($expectedValue, $enum->value);
        self::assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValue(): void
    {
        self::assertSame(AccessSource::TYPE_1, AccessSource::from(1));
        self::assertSame(AccessSource::TYPE_36, AccessSource::from(36));
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        AccessSource::from(999);
    }

    public function testTryFromValidValue(): void
    {
        self::assertSame(AccessSource::TYPE_1, AccessSource::tryFrom(1));
        self::assertSame(AccessSource::TYPE_36, AccessSource::tryFrom(36));
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        self::assertNull(AccessSource::tryFrom(999));
        self::assertNull(AccessSource::tryFrom(-1));
        self::assertNull(AccessSource::tryFrom(0));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (AccessSource $case) => $case->value, AccessSource::cases());
        $uniqueValues = array_unique($values);

        self::assertCount(count($values), $uniqueValues, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (AccessSource $case) => $case->getLabel(), AccessSource::cases());
        $uniqueLabels = array_unique($labels);

        self::assertCount(count($labels), $uniqueLabels, 'All enum labels must be unique');
    }

    public function testToArray(): void
    {
        $expected = [
            'value' => 1,
            'label' => '小程序历史列表',
        ];
        self::assertSame($expected, AccessSource::TYPE_1->toArray());

        $expected = [
            'value' => 2,
            'label' => '搜索',
        ];
        self::assertSame($expected, AccessSource::TYPE_2->toArray());

        $expected = [
            'value' => 10,
            'label' => '其他',
        ];
        self::assertSame($expected, AccessSource::TYPE_10->toArray());

        $expected = [
            'value' => 20,
            'label' => '卡包',
        ];
        self::assertSame($expected, AccessSource::TYPE_20->toArray());

        $expected = [
            'value' => 30,
            'label' => '长按小程序菜单圆点',
        ];
        self::assertSame($expected, AccessSource::TYPE_30->toArray());

        $expected = [
            'value' => 36,
            'label' => '任务栏-我的小程序',
        ];
        self::assertSame($expected, AccessSource::TYPE_36->toArray());
    }
}

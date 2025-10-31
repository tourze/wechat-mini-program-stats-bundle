<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;

/**
 * @internal
 */
#[CoversClass(PerformanceModule::class)]
final class PerformanceModuleTest extends AbstractEnumTestCase
{
    public function testEnumCases(): void
    {
        $cases = PerformanceModule::cases();

        self::assertCount(5, $cases);
        self::assertContains(PerformanceModule::TYPE_16, $cases);
        self::assertContains(PerformanceModule::TYPE_23, $cases);
    }

    #[TestWith([PerformanceModule::TYPE_16, '10016', '打开率'])]
    #[TestWith([PerformanceModule::TYPE_17, '10017', '启动各阶段耗时'])]
    #[TestWith([PerformanceModule::TYPE_21, '10021', '页面切换耗时'])]
    #[TestWith([PerformanceModule::TYPE_22, '10022', '内存指标'])]
    #[TestWith([PerformanceModule::TYPE_23, '10023', '内存异常'])]
    public function testValueAndLabel(PerformanceModule $enum, string $expectedValue, string $expectedLabel): void
    {
        self::assertSame($expectedValue, $enum->value);
        self::assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValue(): void
    {
        self::assertSame(PerformanceModule::TYPE_16, PerformanceModule::from('10016'));
        self::assertSame(PerformanceModule::TYPE_23, PerformanceModule::from('10023'));
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        PerformanceModule::from('99999');
    }

    public function testTryFromValidValue(): void
    {
        self::assertSame(PerformanceModule::TYPE_16, PerformanceModule::tryFrom('10016'));
        self::assertSame(PerformanceModule::TYPE_23, PerformanceModule::tryFrom('10023'));
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        self::assertNull(PerformanceModule::tryFrom('99999'));
        self::assertNull(PerformanceModule::tryFrom('invalid'));
        self::assertNull(PerformanceModule::tryFrom(''));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (PerformanceModule $case) => $case->value, PerformanceModule::cases());
        $uniqueValues = array_unique($values);

        self::assertCount(count($values), $uniqueValues, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (PerformanceModule $case) => $case->getLabel(), PerformanceModule::cases());
        $uniqueLabels = array_unique($labels);

        self::assertCount(count($labels), $uniqueLabels, 'All enum labels must be unique');
    }

    public function testToArray(): void
    {
        $expected = [
            'value' => '10016',
            'label' => '打开率',
        ];
        self::assertSame($expected, PerformanceModule::TYPE_16->toArray());

        $expected = [
            'value' => '10017',
            'label' => '启动各阶段耗时',
        ];
        self::assertSame($expected, PerformanceModule::TYPE_17->toArray());

        $expected = [
            'value' => '10021',
            'label' => '页面切换耗时',
        ];
        self::assertSame($expected, PerformanceModule::TYPE_21->toArray());

        $expected = [
            'value' => '10022',
            'label' => '内存指标',
        ];
        self::assertSame($expected, PerformanceModule::TYPE_22->toArray());

        $expected = [
            'value' => '10023',
            'label' => '内存异常',
        ];
        self::assertSame($expected, PerformanceModule::TYPE_23->toArray());
    }
}

<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramStatsBundle\Enum\CostTimeType;

/**
 * @internal
 */
#[CoversClass(CostTimeType::class)]
final class CostTimeTypeTest extends AbstractEnumTestCase
{
    public function testEnumCases(): void
    {
        $cases = CostTimeType::cases();

        self::assertCount(3, $cases);
        self::assertContains(CostTimeType::Launch, $cases);
        self::assertContains(CostTimeType::Render, $cases);
    }

    #[TestWith([CostTimeType::Launch, 1, '启动总耗时'])]
    #[TestWith([CostTimeType::Download, 2, '下载耗时'])]
    #[TestWith([CostTimeType::Render, 3, '初次渲染耗时'])]
    public function testValueAndLabel(CostTimeType $enum, int $expectedValue, string $expectedLabel): void
    {
        self::assertSame($expectedValue, $enum->value);
        self::assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValue(): void
    {
        self::assertSame(CostTimeType::Launch, CostTimeType::from(1));
        self::assertSame(CostTimeType::Download, CostTimeType::from(2));
        self::assertSame(CostTimeType::Render, CostTimeType::from(3));
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        CostTimeType::from(999);
    }

    public function testTryFromValidValue(): void
    {
        self::assertSame(CostTimeType::Launch, CostTimeType::tryFrom(1));
        self::assertSame(CostTimeType::Download, CostTimeType::tryFrom(2));
        self::assertSame(CostTimeType::Render, CostTimeType::tryFrom(3));
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        self::assertNull(CostTimeType::tryFrom(999));
        self::assertNull(CostTimeType::tryFrom(-1));
        self::assertNull(CostTimeType::tryFrom(0));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CostTimeType $case) => $case->value, CostTimeType::cases());
        $uniqueValues = array_unique($values);

        self::assertCount(count($values), $uniqueValues, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CostTimeType $case) => $case->getLabel(), CostTimeType::cases());
        $uniqueLabels = array_unique($labels);

        self::assertCount(count($labels), $uniqueLabels, 'All enum labels must be unique');
    }

    public function testToArray(): void
    {
        $expected = [
            'value' => 1,
            'label' => '启动总耗时',
        ];
        self::assertSame($expected, CostTimeType::Launch->toArray());

        $expected = [
            'value' => 2,
            'label' => '下载耗时',
        ];
        self::assertSame($expected, CostTimeType::Download->toArray());

        $expected = [
            'value' => 3,
            'label' => '初次渲染耗时',
        ];
        self::assertSame($expected, CostTimeType::Render->toArray());
    }
}

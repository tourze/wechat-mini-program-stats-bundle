<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramStatsBundle\Enum\AccessDepth;

/**
 * @internal
 */
#[CoversClass(AccessDepth::class)]
final class AccessDepthTest extends AbstractEnumTestCase
{
    public function testEnumCases(): void
    {
        $cases = AccessDepth::cases();

        self::assertCount(7, $cases);
        self::assertContains(AccessDepth::TYPE_1, $cases);
        self::assertContains(AccessDepth::TYPE_7, $cases);
    }

    #[TestWith([AccessDepth::TYPE_1, 1, '1 页'])]
    #[TestWith([AccessDepth::TYPE_2, 2, '2 页'])]
    #[TestWith([AccessDepth::TYPE_3, 3, '3 页'])]
    #[TestWith([AccessDepth::TYPE_4, 4, '4 页'])]
    #[TestWith([AccessDepth::TYPE_5, 5, '5 页'])]
    #[TestWith([AccessDepth::TYPE_6, 6, '6-10 页'])]
    #[TestWith([AccessDepth::TYPE_7, 7, '>10 页'])]
    public function testValueAndLabel(AccessDepth $enum, int $expectedValue, string $expectedLabel): void
    {
        self::assertSame($expectedValue, $enum->value);
        self::assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValue(): void
    {
        self::assertSame(AccessDepth::TYPE_1, AccessDepth::from(1));
        self::assertSame(AccessDepth::TYPE_7, AccessDepth::from(7));
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        AccessDepth::from(999);
    }

    public function testTryFromValidValue(): void
    {
        self::assertSame(AccessDepth::TYPE_1, AccessDepth::tryFrom(1));
        self::assertSame(AccessDepth::TYPE_7, AccessDepth::tryFrom(7));
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        self::assertNull(AccessDepth::tryFrom(999));
        self::assertNull(AccessDepth::tryFrom(-1));
        self::assertNull(AccessDepth::tryFrom(0));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (AccessDepth $case) => $case->value, AccessDepth::cases());
        $uniqueValues = array_unique($values);

        self::assertCount(count($values), $uniqueValues, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (AccessDepth $case) => $case->getLabel(), AccessDepth::cases());
        $uniqueLabels = array_unique($labels);

        self::assertCount(count($labels), $uniqueLabels, 'All enum labels must be unique');
    }

    public function testToArray(): void
    {
        $expected = [
            'value' => 1,
            'label' => '1 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_1->toArray());

        $expected = [
            'value' => 2,
            'label' => '2 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_2->toArray());

        $expected = [
            'value' => 3,
            'label' => '3 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_3->toArray());

        $expected = [
            'value' => 4,
            'label' => '4 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_4->toArray());

        $expected = [
            'value' => 5,
            'label' => '5 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_5->toArray());

        $expected = [
            'value' => 6,
            'label' => '6-10 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_6->toArray());

        $expected = [
            'value' => 7,
            'label' => '>10 页',
        ];
        self::assertSame($expected, AccessDepth::TYPE_7->toArray());
    }
}

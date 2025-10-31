<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramStatsBundle\Enum\AccessStayTime;

/**
 * @internal
 */
#[CoversClass(AccessStayTime::class)]
final class AccessStayTimeTest extends AbstractEnumTestCase
{
    public function testEnumCases(): void
    {
        $cases = AccessStayTime::cases();

        self::assertCount(8, $cases);
        self::assertContains(AccessStayTime::TYPE_1, $cases);
        self::assertContains(AccessStayTime::TYPE_2, $cases);
        self::assertContains(AccessStayTime::TYPE_3, $cases);
        self::assertContains(AccessStayTime::TYPE_4, $cases);
        self::assertContains(AccessStayTime::TYPE_5, $cases);
        self::assertContains(AccessStayTime::TYPE_6, $cases);
        self::assertContains(AccessStayTime::TYPE_7, $cases);
        self::assertContains(AccessStayTime::TYPE_8, $cases);
    }

    #[TestWith([AccessStayTime::TYPE_1, 1, '0-2s'])]
    #[TestWith([AccessStayTime::TYPE_2, 2, '3-5s'])]
    #[TestWith([AccessStayTime::TYPE_3, 3, '6-10s'])]
    #[TestWith([AccessStayTime::TYPE_4, 4, '11-20s'])]
    #[TestWith([AccessStayTime::TYPE_5, 5, '20-30s'])]
    #[TestWith([AccessStayTime::TYPE_6, 6, '30-50s'])]
    #[TestWith([AccessStayTime::TYPE_7, 7, '50-100s'])]
    #[TestWith([AccessStayTime::TYPE_8, 8, '>100s'])]
    public function testValueAndLabel(AccessStayTime $enum, int $expectedValue, string $expectedLabel): void
    {
        self::assertSame($expectedValue, $enum->value);
        self::assertSame($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValue(): void
    {
        self::assertSame(AccessStayTime::TYPE_1, AccessStayTime::from(1));
        self::assertSame(AccessStayTime::TYPE_8, AccessStayTime::from(8));
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        AccessStayTime::from(999);
    }

    public function testTryFromValidValue(): void
    {
        self::assertSame(AccessStayTime::TYPE_1, AccessStayTime::tryFrom(1));
        self::assertSame(AccessStayTime::TYPE_8, AccessStayTime::tryFrom(8));
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        self::assertNull(AccessStayTime::tryFrom(999));
        self::assertNull(AccessStayTime::tryFrom(-1));
        self::assertNull(AccessStayTime::tryFrom(0));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (AccessStayTime $case) => $case->value, AccessStayTime::cases());
        $uniqueValues = array_unique($values);

        self::assertCount(count($values), $uniqueValues, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (AccessStayTime $case) => $case->getLabel(), AccessStayTime::cases());
        $uniqueLabels = array_unique($labels);

        self::assertCount(count($labels), $uniqueLabels, 'All enum labels must be unique');
    }

    public function testToArray(): void
    {
        $expected = [
            'value' => 1,
            'label' => '0-2s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_1->toArray());

        $expected = [
            'value' => 2,
            'label' => '3-5s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_2->toArray());

        $expected = [
            'value' => 3,
            'label' => '6-10s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_3->toArray());

        $expected = [
            'value' => 4,
            'label' => '11-20s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_4->toArray());

        $expected = [
            'value' => 5,
            'label' => '20-30s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_5->toArray());

        $expected = [
            'value' => 6,
            'label' => '30-50s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_6->toArray());

        $expected = [
            'value' => 7,
            'label' => '50-100s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_7->toArray());

        $expected = [
            'value' => 8,
            'label' => '>100s',
        ];
        self::assertSame($expected, AccessStayTime::TYPE_8->toArray());
    }
}

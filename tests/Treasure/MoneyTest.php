<?php

namespace Tests\Treasure;

use Dollars\Treasure\Money;
use InvalidArgumentException;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class MoneyAmountTest
 * @package Tests
 */
final class MoneyTest extends \Tests\TestCase
{
    use ProphecyTrait;

    public function testCannotParseNegativeValue(): void
    {
        $money = new Money(-0.01);

        $this->expectException(InvalidArgumentException::class);

        $money->toWords();
    }

    public function testCannotParseValuesGreaterThanOneThousand(): void
    {
        $money = new Money(1000.01);

        $this->expectException(InvalidArgumentException::class);

        $money->toWords();
    }

    public function testItReturnsStringZeroInt(): void
    {
        $money = new Money(0.00);

        self::assertEquals(
            'zero',
            $money->toWords()
        );
    }

    public function testAlternativeCheckWriting(): void
    {
        $money = new Money(1.12);

        self::assertEquals(
            'one dollar and 12/100',
            $money->toWords(true)
        );
    }

    /**
     * @param $amount
     * @param $expected
     *
     * @dataProvider moneyAmountProvider
     */
    public function testItReturnsRightAmountInWords($amount, $expected): void
    {
        $money = new Money($amount);

        self::assertEquals(
            $expected,
            $money->toWords()
        );
    }

    public function moneyAmountProvider(): array
    {
        return [
            [0, 'zero'],
            [0.0, 'zero'],
            [0.01, 'one cent'],
            [0.02, 'two cents'],
            [0.9, 'ninety cents'],
            [0.19, 'nineteen cents'],
            [0.2, 'twenty cents'],
            [0.33, 'thirty three cents'],
            [0.333, 'thirty three cents'],
            [0.49, 'forty nine cents'],
            [0.99, 'ninety nine cents'],
            [1.00, 'one dollar'],
            [1.99, 'one dollar and ninety nine cents'],
            [2.00, 'two dollars'],
            [260.01, 'two hundred sixty dollars and one cent'],
            [120.81, 'one hundred twenty dollars and eighty one cents'],
            [890.1, 'eight hundred ninety dollars and ten cents'],
            [156.29, 'one hundred fifty six dollars and twenty nine cents'],
            [513.65, 'five hundred thirteen dollars and sixty five cents'],
            [957.03, 'nine hundred fifty seven dollars and three cents'],
            [999.99, 'nine hundred ninety nine dollars and ninety nine cents'],
            [1000, 'one thousand dollars'],
            [1000.00, 'one thousand dollars'],
        ];
    }
}

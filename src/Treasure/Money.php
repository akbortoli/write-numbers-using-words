<?php

namespace Dollars\Treasure;

use InvalidArgumentException;

/**
 * Class Money
 * @package Dollars\Support
 */
class Money
{
    /**
     * @var string[]
     */
    private $ones = [
        "",
        "one",
        "two",
        "three",
        "four",
        "five",
        "six",
        "seven",
        "eight",
        "nine",
        "ten",
        "eleven",
        "twelve",
        "thirteen",
        "fourteen",
        "fifteen",
        "sixteen",
        "seventeen",
        "eighteen",
        "nineteen"
    ];

    /**
     * @var string[]
     */
    private $tens = [
        "",
        "ten",
        "twenty",
        "thirty",
        "forty",
        "fifty",
        "sixty",
        "seventy",
        "eighty",
        "ninety"
    ];

    /**
     * @var float
     */
    protected $amount;

    /**
     * Money constructor.
     *
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param bool $altCheckCents
     * @return string
     */
    public function toWords(bool $altCheckCents = false): string
    {
        $amount = $this->getAmount();

        if ($amount === 0.0) {
            return "zero";
        }

        if ($amount < 0.0) {
            throw new InvalidArgumentException(__METHOD__ . ' only accepts positive values.');
        }

        if ($amount > 1000.00) {
            throw new InvalidArgumentException(__METHOD__ . ' only accepts values up to 1000.');
        }

        $intAmount = (int) $amount;
        $cents = (int) round(($amount - $intAmount) * 100);

        $dollarPart = ' dollar' . ($intAmount === 1 ? '' : 's');

        // Convert input into words.
        $words = $this->words($intAmount);

        if ($intAmount > 0) {
            $words .= $dollarPart;
        }

        if ($cents > 0) {
            if ($altCheckCents) {
                $suffix = "{$cents}/100";
            } else {
                $centsPart = ' cent' . ($cents === 1 ? '' : 's');
                $suffix = $this->words($cents) . $centsPart;
            }

            $words .= ($intAmount > 0 ? ' and ' : '') . $suffix;
        }

        return $words;
    }

    /**
     * @param int $amount
     * @return string
     */
    public function words(int $amount): string
    {
        if ($amount < 20) {
            return $this->ones[$amount];
        }

        if ($amount < 100) {
            $concat = $amount % 10 !== 0 ? ' ' : '';

            return $this->tens[$amount / 10] . $concat . $this->ones[$amount % 10];
        }

        if ($amount < 1000) {
            $words = $amount % 100 !== 0 ? ' ' : '';
            $words .= $this->words($amount % 100);

            return $this->ones[$amount / 100] . " hundred" . $words;
        }

        $words = $amount % 1000 !== 0 ? ' ' : '';
        $words .= $this->words($amount % 1000);

        return $this->words($amount / 1000) . " thousand" . $words;
    }
}

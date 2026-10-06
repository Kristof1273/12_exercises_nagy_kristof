<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Exercise 02 - the same assertion is run against every data set that a
 * data provider returns.
 */
final class CalculatorDataProviderTest extends TestCase
{
    /**
     * @return array<string, array{int|float, int|float, int|float}>
     */
    public static function additionProvider(): array
    {
        return [
            // description              => [a, b, expected]
            'two positive numbers'      => [2, 3, 5],
            'two negative numbers'      => [-2, -3, -5],
            'positive plus negative'    => [10, -4, 6],
            'negative plus positive'    => [-10, 4, -6],
            'zero plus zero'            => [0, 0, 0],
            'zero plus a number'        => [0, 7, 7],
            'a number plus its inverse' => [5, -5, 0],
            'floating point numbers'    => [1.5, 2.25, 3.75],
        ];
    }

    #[DataProvider('additionProvider')]
    public function testAddReturnsTheExpectedSum(int|float $a, int|float $b, int|float $expected): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->add($a, $b);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{int|float, int|float, int|float}>
     */
    public static function divisionProvider(): array
    {
        return [
            // description                  => [dividend, divisor, expected]
            'exact division'                => [20, 4, 5],
            'negative dividend'             => [-20, 4, -5],
            'negative divisor'              => [20, -4, -5],
            'both operands negative'        => [-20, -4, 5],
            'zero divided by a number'      => [0, 5, 0],
            'division with a fraction'      => [1, 8, 0.125],
            'floating point operands'       => [7.5, 2.5, 3.0],
        ];
    }

    #[DataProvider('divisionProvider')]
    public function testDivideReturnsTheExpectedQuotient(
        int|float $dividend,
        int|float $divisor,
        int|float $expected,
    ): void {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->divide($dividend, $divisor);

        // Assert
        $this->assertSame($expected, $result);
    }
}

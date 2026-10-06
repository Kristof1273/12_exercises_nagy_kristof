<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Calculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Exercise 01 - first unit tests, written with the AAA pattern
 * (Arrange, Act, Assert).
 */
final class CalculatorTest extends TestCase
{
    public function testAddReturnsTheSumOfTwoNumbers(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->add(2, 3);

        // Assert
        $this->assertSame(5, $result);
    }

    public function testSubtractReturnsTheDifferenceOfTwoNumbers(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->subtract(10, 4);

        // Assert
        $this->assertSame(6, $result);
    }

    public function testMultiplyReturnsTheProductOfTwoNumbers(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->multiply(6, 7);

        // Assert
        $this->assertSame(42, $result);
    }

    public function testDivideReturnsTheQuotientOfTwoNumbers(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->divide(20, 4);

        // Assert
        $this->assertSame(5, $result);
    }

    public function testDivideCanReturnAFraction(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->divide(1, 4);

        // Assert
        $this->assertSame(0.25, $result);
    }

    public function testDivideByZeroThrowsAnInvalidArgumentException(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Assert (the expectation has to be registered before the act step)
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero is not allowed.');

        // Act
        $calculator->divide(10, 0);
    }
}

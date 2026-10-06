<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

/**
 * A simple calculator supporting the four basic arithmetic operations.
 */
final class Calculator
{
    public function add(int|float $a, int|float $b): int|float
    {
        return $a + $b;
    }

    public function subtract(int|float $a, int|float $b): int|float
    {
        return $a - $b;
    }

    public function multiply(int|float $a, int|float $b): int|float
    {
        return $a * $b;
    }

    /**
     * @throws InvalidArgumentException when the divisor is zero
     */
    public function divide(int|float $dividend, int|float $divisor): int|float
    {
        if ($divisor == 0) {
            throw new InvalidArgumentException('Division by zero is not allowed.');
        }

        return $dividend / $divisor;
    }
}

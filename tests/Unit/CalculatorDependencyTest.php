<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

/**
 * Exercise 02 - a chain of three dependent tests.
 *
 * Each test receives the return value of the test it depends on:
 *
 *   1. create an empty list of calculation results
 *   2. fill the list with results
 *   3. verify the final state of the list
 *
 * If a test in the chain fails, the tests depending on it are skipped.
 */
final class CalculatorDependencyTest extends TestCase
{
    /**
     * @return list<int|float>
     */
    public function testResultListStartsEmpty(): array
    {
        // Arrange & Act
        $results = [];

        // Assert
        $this->assertEmpty($results);

        return $results;
    }

    /**
     * @param list<int|float> $results
     *
     * @return list<int|float>
     */
    #[Depends('testResultListStartsEmpty')]
    public function testResultsCanBeAddedToTheList(array $results): array
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $results[] = $calculator->add(2, 3);
        $results[] = $calculator->multiply(4, 5);
        $results[] = $calculator->subtract(1, 11);

        // Assert
        $this->assertCount(3, $results);

        return $results;
    }

    /**
     * @param list<int|float> $results
     */
    #[Depends('testResultsCanBeAddedToTheList')]
    public function testResultListHoldsTheFinalState(array $results): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $total = 0;
        foreach ($results as $result) {
            $total = $calculator->add($total, $result);
        }

        // Assert
        $this->assertSame([5, 20, -10], $results);
        $this->assertSame(15, $total);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Task;
use PHPUnit\Framework\TestCase;

/**
 * Exercise 03 - Task is a small value object with an ID and a name.
 */
final class TaskTest extends TestCase
{
    public function testTaskExposesItsIdAndName(): void
    {
        // Arrange
        $task = new Task(7, 'Write tests');

        // Act
        $id = $task->getId();
        $name = $task->getName();

        // Assert
        $this->assertSame(7, $id);
        $this->assertSame('Write tests', $name);
    }
}

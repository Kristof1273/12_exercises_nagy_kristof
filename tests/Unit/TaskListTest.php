<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\TaskList;
use PHPUnit\Framework\TestCase;

/**
 * Exercise 03 - TaskList, built test-first (Red -> Green -> Refactor).
 */
final class TaskListTest extends TestCase
{
    public function testGetTaskReturnsNullWhenTheListIsEmpty(): void
    {
        // Arrange
        $taskList = new TaskList();

        // Act
        $task = $taskList->getTask(0);

        // Assert
        $this->assertNull($task);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Task;
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

    public function testAddedTaskCanBeRetrieved(): void
    {
        // Arrange
        $taskList = new TaskList();
        $task = new Task(1, 'Write tests');

        // Act
        $taskList->addTask($task);
        $retrievedTask = $taskList->getTask(0);

        // Assert
        $this->assertSame($task, $retrievedTask);
    }
}

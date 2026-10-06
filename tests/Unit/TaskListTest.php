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

    public function testMultipleTasksCanBeRetrievedByTheirIndex(): void
    {
        // Arrange
        $taskList = new TaskList();
        $firstTask = new Task(1, 'Write tests');
        $secondTask = new Task(2, 'Make them pass');
        $thirdTask = new Task(3, 'Refactor');

        // Act
        $taskList->addTask($firstTask);
        $taskList->addTask($secondTask);
        $taskList->addTask($thirdTask);

        // Assert
        $this->assertSame($firstTask, $taskList->getTask(0));
        $this->assertSame($secondTask, $taskList->getTask(1));
        $this->assertSame($thirdTask, $taskList->getTask(2));
    }

    public function testGetTaskReturnsNullWhenTheIndexIsOutOfRange(): void
    {
        // Arrange
        $taskList = new TaskList();
        $taskList->addTask(new Task(1, 'Write tests'));
        $taskList->addTask(new Task(2, 'Make them pass'));

        // Act
        $task = $taskList->getTask(2);

        // Assert
        $this->assertNull($task);
    }
}

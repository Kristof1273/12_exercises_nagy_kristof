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
    private TaskList $taskList;

    protected function setUp(): void
    {
        $this->taskList = new TaskList();
    }

    public function testGetTaskReturnsNullWhenTheListIsEmpty(): void
    {
        // Arrange (an empty list is created in setUp())

        // Act
        $task = $this->taskList->getTask(0);

        // Assert
        $this->assertNull($task);
    }

    public function testAddedTaskCanBeRetrieved(): void
    {
        // Arrange
        $task = new Task(1, 'Write tests');

        // Act
        $this->taskList->addTask($task);
        $retrievedTask = $this->taskList->getTask(0);

        // Assert
        $this->assertSame($task, $retrievedTask);
    }

    public function testMultipleTasksCanBeRetrievedByTheirIndex(): void
    {
        // Arrange
        $firstTask = new Task(1, 'Write tests');
        $secondTask = new Task(2, 'Make them pass');
        $thirdTask = new Task(3, 'Refactor');

        // Act
        $this->taskList->addTask($firstTask);
        $this->taskList->addTask($secondTask);
        $this->taskList->addTask($thirdTask);

        // Assert
        $this->assertSame($firstTask, $this->taskList->getTask(0));
        $this->assertSame($secondTask, $this->taskList->getTask(1));
        $this->assertSame($thirdTask, $this->taskList->getTask(2));
    }

    public function testGetTaskReturnsNullWhenTheIndexIsOutOfRange(): void
    {
        // Arrange
        $this->taskList->addTask(new Task(1, 'Write tests'));
        $this->taskList->addTask(new Task(2, 'Make them pass'));

        // Act
        $task = $this->taskList->getTask(2);

        // Assert
        $this->assertNull($task);
    }

    public function testGetTaskByIdReturnsTheTaskWithTheGivenId(): void
    {
        // Arrange (the IDs deliberately differ from the list indexes)
        $firstTask = new Task(42, 'Write tests');
        $secondTask = new Task(7, 'Make them pass');
        $this->taskList->addTask($firstTask);
        $this->taskList->addTask($secondTask);

        // Act
        $task = $this->taskList->getTaskById(7);

        // Assert
        $this->assertSame($secondTask, $task);
    }

    public function testGetTaskByIdReturnsNullForAnUnknownId(): void
    {
        // Arrange
        $this->taskList->addTask(new Task(42, 'Write tests'));
        $this->taskList->addTask(new Task(7, 'Make them pass'));

        // Act
        $task = $this->taskList->getTaskById(99);

        // Assert
        $this->assertNull($task);
    }
}

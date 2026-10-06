<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Task;
use App\TaskList;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function testGetTaskThrowsAnExceptionForANegativeIndex(): void
    {
        // Arrange
        $this->taskList->addTask(new Task(1, 'Write tests'));

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index must not be negative, -1 given.');

        // Act
        $this->taskList->getTask(-1);
    }

    public function testGetTaskByIdThrowsAnExceptionForANegativeId(): void
    {
        // Arrange
        $this->taskList->addTask(new Task(1, 'Write tests'));

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The ID must not be negative, -5 given.');

        // Act
        $this->taskList->getTaskById(-5);
    }

    public function testSortByNameOrdersTheTasksAlphabetically(): void
    {
        // Arrange
        $this->addUnsortedTasks();

        // Act
        $this->taskList->sortByName();

        // Assert
        $this->assertSame(
            ['Make them pass', 'Refactor', 'Write tests'],
            $this->taskNamesInListOrder(),
        );
    }

    public function testSortByIdOrdersTheTasksNumerically(): void
    {
        // Arrange
        $this->addUnsortedTasks();

        // Act
        $this->taskList->sortById();

        // Assert
        $this->assertSame([1, 2, 3], $this->taskIdsInListOrder());
    }

    public function testSortByThrowsAnExceptionForAnInvalidColumn(): void
    {
        // Arrange
        $this->addUnsortedTasks();

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot sort by unknown column "priority".');

        // Act
        $this->taskList->sortBy('priority');
    }

    /**
     * @return array<string, array{string, list<int>}>
     */
    public static function validSortColumnProvider(): array
    {
        return [
            // description    => [column, expected order of the task IDs]
            'sort by id'      => ['id', [1, 2, 3]],
            'sort by name'    => ['name', [2, 3, 1]],
        ];
    }

    /**
     * @param list<int> $expectedIds
     */
    #[DataProvider('validSortColumnProvider')]
    public function testSortByOrdersTheTasksByAValidColumn(string $column, array $expectedIds): void
    {
        // Arrange
        $this->addUnsortedTasks();

        // Act
        $this->taskList->sortBy($column);

        // Assert
        $this->assertSame($expectedIds, $this->taskIdsInListOrder());
    }

    /**
     * Adds three tasks whose insertion order differs from both their ID
     * order (1, 2, 3) and their name order (2, 3, 1).
     */
    private function addUnsortedTasks(): void
    {
        $this->taskList->addTask(new Task(3, 'Refactor'));
        $this->taskList->addTask(new Task(1, 'Write tests'));
        $this->taskList->addTask(new Task(2, 'Make them pass'));
    }

    /**
     * @return list<int>
     */
    private function taskIdsInListOrder(): array
    {
        $ids = [];
        for ($index = 0; ($task = $this->taskList->getTask($index)) !== null; $index++) {
            $ids[] = $task->getId();
        }

        return $ids;
    }

    /**
     * @return list<string>
     */
    private function taskNamesInListOrder(): array
    {
        $names = [];
        for ($index = 0; ($task = $this->taskList->getTask($index)) !== null; $index++) {
            $names[] = $task->getName();
        }

        return $names;
    }
}

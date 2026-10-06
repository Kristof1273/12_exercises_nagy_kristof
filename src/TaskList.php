<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

/**
 * A collection of Task objects.
 */
final class TaskList
{
    public const COLUMN_ID = 'id';
    public const COLUMN_NAME = 'name';

    /** @var list<Task> */
    private array $tasks = [];

    public function addTask(Task $task): void
    {
        $this->tasks[] = $task;
    }

    /**
     * @throws InvalidArgumentException when the index is negative
     */
    public function getTask(int $index): ?Task
    {
        if ($index < 0) {
            throw new InvalidArgumentException(
                sprintf('The index must not be negative, %d given.', $index),
            );
        }

        return $this->tasks[$index] ?? null;
    }

    /**
     * @throws InvalidArgumentException when the ID is negative
     */
    public function getTaskById(int $id): ?Task
    {
        if ($id < 0) {
            throw new InvalidArgumentException(
                sprintf('The ID must not be negative, %d given.', $id),
            );
        }

        foreach ($this->tasks as $task) {
            if ($task->getId() === $id) {
                return $task;
            }
        }

        return null;
    }

    public function sortByName(): void
    {
        $this->sortBy(self::COLUMN_NAME);
    }

    public function sortById(): void
    {
        $this->sortBy(self::COLUMN_ID);
    }

    /**
     * Sorts the tasks in ascending order by the given column.
     *
     * @throws InvalidArgumentException when the column is not sortable
     */
    public function sortBy(string $column): void
    {
        $comparator = match ($column) {
            self::COLUMN_ID => static fn (Task $a, Task $b): int => $a->getId() <=> $b->getId(),
            self::COLUMN_NAME => static fn (Task $a, Task $b): int => strcmp($a->getName(), $b->getName()),
            default => throw new InvalidArgumentException(
                sprintf('Cannot sort by unknown column "%s".', $column),
            ),
        };

        usort($this->tasks, $comparator);
    }
}

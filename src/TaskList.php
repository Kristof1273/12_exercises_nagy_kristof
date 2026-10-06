<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

/**
 * A collection of Task objects.
 */
final class TaskList
{
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
        usort(
            $this->tasks,
            static fn (Task $a, Task $b): int => strcmp($a->getName(), $b->getName()),
        );
    }

    public function sortById(): void
    {
        usort(
            $this->tasks,
            static fn (Task $a, Task $b): int => $a->getId() <=> $b->getId(),
        );
    }

    /**
     * @throws InvalidArgumentException when the column is not sortable
     */
    public function sortBy(string $column): void
    {
        if ($column === 'name') {
            usort(
                $this->tasks,
                static fn (Task $a, Task $b): int => strcmp($a->getName(), $b->getName()),
            );

            return;
        }

        if ($column === 'id') {
            usort(
                $this->tasks,
                static fn (Task $a, Task $b): int => $a->getId() <=> $b->getId(),
            );

            return;
        }

        throw new InvalidArgumentException(
            sprintf('Cannot sort by unknown column "%s".', $column),
        );
    }
}

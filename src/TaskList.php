<?php

declare(strict_types=1);

namespace App;

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

    public function getTask(int $index): ?Task
    {
        return $this->tasks[$index] ?? null;
    }

    public function getTaskById(int $id): ?Task
    {
        foreach ($this->tasks as $task) {
            if ($task->getId() === $id) {
                return $task;
            }
        }

        return null;
    }

    public function sortByName(): void
    {
    }

    public function sortById(): void
    {
    }

    public function sortBy(string $column): void
    {
    }
}

<?php

declare(strict_types=1);

namespace App;

/**
 * A collection of Task objects.
 */
final class TaskList
{
    private ?Task $task = null;

    public function addTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getTask(int $index): ?Task
    {
        return $this->task;
    }

    public function getTaskById(int $id): ?Task
    {
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

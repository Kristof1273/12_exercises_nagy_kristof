<?php

declare(strict_types=1);

namespace App;

/**
 * A collection of Task objects.
 */
final class TaskList
{
    public function addTask(Task $task): void
    {
    }

    public function getTask(int $index): ?Task
    {
        return null;
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

<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 * Stores Task objects in a relational database through PDO.
 *
 * Expected schema:
 *
 *   CREATE TABLE tasks (id INTEGER PRIMARY KEY, name TEXT NOT NULL)
 */
final class TaskRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Inserts the task, or updates its name when a task with the same ID
     * has already been saved.
     */
    public function save(Task $task): void
    {
        $sql = $this->findById($task->getId()) === null
            ? 'INSERT INTO tasks (id, name) VALUES (:id, :name)'
            : 'UPDATE tasks SET name = :name WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'id' => $task->getId(),
            'name' => $task->getName(),
        ]);
    }

    public function findById(int $id): ?Task
    {
        $statement = $this->pdo->prepare('SELECT id, name FROM tasks WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $this->createTaskFromRow($row);
    }

    /**
     * @return list<Task> all saved tasks, ordered by their ID
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query('SELECT id, name FROM tasks ORDER BY id');

        return array_map(
            $this->createTaskFromRow(...),
            $statement->fetchAll(PDO::FETCH_ASSOC),
        );
    }

    /**
     * @param array{id: int|string, name: string} $row
     */
    private function createTaskFromRow(array $row): Task
    {
        return new Task((int) $row['id'], $row['name']);
    }
}

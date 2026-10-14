<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Task;
use App\TaskRepository;
use PDO;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

/**
 * Extra Mile - integration test.
 */
#[RequiresPhpExtension('pdo_sqlite')]
final class TaskRepositoryTest extends TestCase
{
    private PDO $pdo;
    private TaskRepository $repository;

    /**
     * Runs before every test: each test gets its own, empty in-memory
     * database with a freshly created schema, so the tests cannot
     * influence each other.
     */
    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE tasks (id INTEGER PRIMARY KEY, name TEXT NOT NULL)');

        $this->repository = new TaskRepository($this->pdo);
    }

    public function testSavedTaskCanBeRetrievedById(): void
    {
        // Arrange
        $task = new Task(1, 'Write tests');

        // Act
        $this->repository->save($task);
        $retrievedTask = $this->repository->findById(1);

        // Assert
        $this->assertEquals($task, $retrievedTask);
    }

    public function testSaveWritesARowIntoTheTasksTable(): void
    {
        // Arrange
        $task = new Task(1, 'Write tests');

        // Act
        $this->repository->save($task);

        // Assert: look at the database directly, bypassing the repository
        $rows = $this->pdo->query('SELECT id, name FROM tasks')->fetchAll(PDO::FETCH_ASSOC);
        $this->assertSame([['id' => 1, 'name' => 'Write tests']], $rows);
    }

    public function testSavingATaskWithAnExistingIdUpdatesTheStoredTask(): void
    {
        // Arrange
        $this->repository->save(new Task(1, 'Write tests'));

        // Act
        $this->repository->save(new Task(1, 'Write better tests'));

        // Assert: the row was updated, no second row was inserted
        $this->assertEquals(
            [new Task(1, 'Write better tests')],
            $this->repository->findAll(),
        );
    }

    public function testFindByIdReturnsNullForAnUnknownId(): void
    {
        // Arrange
        $this->repository->save(new Task(1, 'Write tests'));

        // Act
        $task = $this->repository->findById(99);

        // Assert
        $this->assertNull($task);
    }

    public function testFindAllReturnsAllTasksOrderedById(): void
    {
        // Arrange
        $this->repository->save(new Task(3, 'Refactor'));
        $this->repository->save(new Task(1, 'Write tests'));
        $this->repository->save(new Task(2, 'Make them pass'));

        // Act
        $tasks = $this->repository->findAll();

        // Assert
        $this->assertEquals(
            [
                new Task(1, 'Write tests'),
                new Task(2, 'Make them pass'),
                new Task(3, 'Refactor'),
            ],
            $tasks,
        );
    }

    public function testEveryTestStartsWithAnEmptyDatabase(): void
    {
        // Arrange (other tests in this class have saved tasks before)

        // Act
        $tasks = $this->repository->findAll();

        // Assert
        $this->assertSame([], $tasks);
    }
}

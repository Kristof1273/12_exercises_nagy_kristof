<?php

declare(strict_types=1);

namespace App;

/**
 * A single task, identified by an ID and described by a name.
 */
final class Task
{
    public function __construct(int $id, string $name)
    {
    }

    public function getId(): int
    {
    }

    public function getName(): string
    {
    }
}

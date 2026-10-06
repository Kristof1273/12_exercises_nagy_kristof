<?php

declare(strict_types=1);

namespace App;

/**
 * A single task, identified by an ID and described by a name.
 */
final class Task
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

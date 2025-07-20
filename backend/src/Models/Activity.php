<?php

declare(strict_types=1);

namespace App\Models;

class Activity
{
    public function __construct(
        public ?int $id = null,
        public int $userId = 0,
        public string $action = '',
        public ?string $page = null,
        public ?string $createdAt = null,
    ) {}
}
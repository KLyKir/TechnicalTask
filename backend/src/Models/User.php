<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\UserRoleEnum;

class User
{
    public function __construct(
        public ?int $id = null,
        public string $username = '',
        public UserRoleEnum $role = UserRoleEnum::USER,
    ) {}
}
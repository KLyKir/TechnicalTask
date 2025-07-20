<?php

declare(strict_types=1);

namespace App\Enum;

enum UserRoleEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';
}

<?php

declare(strict_types=1);

namespace App\Enum;

enum ActivitiesPageEnum: string
{
    case PAGE_A = 'A';
    case PAGE_B = 'B';
    case LOGIN = 'login';
    case REGISTER = 'register';
}

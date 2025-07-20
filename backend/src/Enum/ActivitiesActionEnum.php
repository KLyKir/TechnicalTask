<?php

declare(strict_types=1);

namespace App\Enum;

enum ActivitiesActionEnum: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case BUTTON_CLICK = 'button-click';
    case REGISTRATION = 'registration';
    case VIEW_PAGE = 'view-page';
}

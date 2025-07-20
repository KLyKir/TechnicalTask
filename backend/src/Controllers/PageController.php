<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DataServices\ActivityDataService;
use App\Enum\ActivitiesActionEnum;
use App\Enum\ActivitiesPageEnum;
use App\Response;

readonly class PageController
{
    public function __construct(public ActivityDataService $activityDataService) {}

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function viewPage(ActivitiesPageEnum $page): Response
    {
        if (!$this->isAuthenticated()) {
            return Response::error('Unauthorized', 401);
        }

        $this->activityDataService->log($_SESSION['user_id'], ActivitiesActionEnum::VIEW_PAGE, $page);

        return Response::success();
    }

    public function buttonClick(ActivitiesPageEnum $page): Response
    {
        if (!$this->isAuthenticated()) {
            return Response::error('Unauthorized', 401);
        }

        $this->activityDataService->log($_SESSION['user_id'], ActivitiesActionEnum::BUTTON_CLICK, $page);

        return Response::success();
    }

    public function download(): Response
    {
        if (!$this->isAuthenticated()) {
            return Response::error('Unauthorized', 401);
        }

        $this->activityDataService->log($_SESSION['user_id'], ActivitiesActionEnum::BUTTON_CLICK, ActivitiesPageEnum::PAGE_B);

        return Response::download('/var/www/html/sample.exe', 'sample.exe');
    }
}
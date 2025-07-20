<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DataServices\ActivityDataService;
use App\Enum\UserRoleEnum;
use App\Request;
use App\Response;

readonly class StatsController
{
    public function __construct(public ActivityDataService $activityDataService) {}

    public function getStats(Request $request): Response
    {
        if ($_SESSION['role'] !== UserRoleEnum::ADMIN->value) {
            return Response::error('Forbidden', 403);
        }

        $filters = $request->all();
        $stats = $this->activityDataService->getStats($filters);

        return Response::json($stats);
    }
}
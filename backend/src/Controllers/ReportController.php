<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DataServices\ActivityDataService;
use App\DTO\ReportStatsDTO;
use App\Enum\UserRoleEnum;
use App\Request;
use App\Response;
use App\Services\ReportService;

readonly class ReportController
{
    public function __construct(public ActivityDataService $activityDataService, public ReportService $reportService) {}

    public function getReports(Request $request): Response
    {
        if ($_SESSION['role'] !== UserRoleEnum::ADMIN->value) {
            return Response::error('Forbidden', 403);
        }

        $startDate = $request->input('startDate') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $request->input('endDate') ?? date('Y-m-d');

        $reports = $this->activityDataService->getReportData($startDate, $endDate);
        $reportsArray = array_map(fn(ReportStatsDTO $dto) => get_object_vars($dto), $reports);

        return Response::success(['reports' => $reportsArray]);
    }

    public function downloadReports(Request $request): Response
    {
        if ($_SESSION['role'] !== UserRoleEnum::ADMIN->value) {
            return Response::error('Forbidden', 403);
        }

        $startDate = $request->input('startDate') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $request->input('endDate') ?? date('Y-m-d');

        $reports = $this->activityDataService->getReportData($startDate, $endDate);
        $csvData = $this->reportService->generateCsv($reports);

        return Response::file(
            $csvData,
            'report.csv',
            'text/csv'
        );
    }
}
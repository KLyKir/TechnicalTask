<?php

use App\Container;
use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Controllers\ReportController;
use App\Controllers\StatsController;
use App\Enum\ActivitiesPageEnum;
use App\Request;
use App\Response;

require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Controllers/PageController.php';
require_once __DIR__ . '/../src/Controllers/StatsController.php';
require_once __DIR__ . '/../src/Controllers/ReportController.php';

$container = new Container();

$request = Request::capture();
$uri = $request->uri;
$method = $request->method;

try {
    switch ($uri) {
        case '/api/login':
            if ($method === 'POST') {
                $controller = $container->make(AuthController::class);
                $controller->login($request)->send();
            }
            break;

        case '/api/register':
            if ($method === 'POST') {
                $controller = $container->make(AuthController::class);
                $controller->register($request)->send();
            }
            break;

        case '/api/logout':
            if ($method === 'POST') {
                $controller = $container->make(AuthController::class);
                $controller->logout()->send();
            }
            break;

        case '/api/page-a':
            $controller = $container->make(PageController::class);
            if ($method === 'GET') {
                $controller->viewPage(ActivitiesPageEnum::PAGE_A)->send();
            } elseif ($method === 'POST') {
                $controller->buttonClick(ActivitiesPageEnum::PAGE_A)->send();
            }
            break;

        case '/api/page-b':
            $controller = $container->make(PageController::class);
            if ($method === 'GET') {
                $controller->viewPage(ActivitiesPageEnum::PAGE_B)->send();
            } elseif ($method === 'POST') {
                $controller->buttonClick(ActivitiesPageEnum::PAGE_B)->send();
            }
            break;

        case '/api/download':
            if ($method === 'GET') {
                $controller = $container->make(PageController::class);
                $controller->download()->send();
            }
            break;

        case '/api/stats':
            if ($method === 'POST') {
                $controller = $container->make(StatsController::class);
                $controller->getStats($request)->send();
            }
            break;

        case '/api/reports':
            if ($method === 'POST') {
                $controller = $container->make(ReportController::class);
                $controller->getReports($request)->send();
            }
            break;

        case '/api/reports/download':
            if ($method === 'GET') {
                $controller = $container->make(ReportController::class);
                $controller->downloadReports($request)->send();
            }
            break;

        default:
            Response::error('Not found', 404)->send();
    }
} catch (Throwable $e) {
    Response::error('Internal Server Error: ' . $e->getMessage(), 500)->send();
}

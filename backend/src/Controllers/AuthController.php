<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DataServices\ActivityDataService;
use App\DataServices\UserDataService;
use App\Enum\ActivitiesActionEnum;
use App\Request;
use App\Response;

readonly class AuthController
{
    public function __construct(
        public ActivityDataService $activityDataService,
        public UserDataService $userDataService,
    ) {}

    public function register(Request $request): Response
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($this->userDataService->register($username, $password)) {
            $user = $this->userDataService->login($username, $password);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role->value;
            $this->activityDataService->log($user->id, ActivitiesActionEnum::REGISTRATION);

            return Response::success(['role' => $user->role->value]);
        }

        return Response::error('Registration failed', 400);
    }

    public function login(Request $request): Response
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->userDataService->login($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role->value;
            $this->activityDataService->log($user->id, ActivitiesActionEnum::LOGIN);

            return Response::success(['role' => $user->role->value]);
        }

        return Response::error('Invalid credentials', 401);
    }

    public function logout(): Response
    {
        $this->activityDataService->log($_SESSION['user_id'] ?? 0, ActivitiesActionEnum::LOGOUT);
        session_destroy();

        return Response::success();
    }
}
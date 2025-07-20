<?php

declare(strict_types=1);

namespace App\DataServices;

use App\Config\Database;
use App\Enum\UserRoleEnum;
use App\Models\User;
use PDO;

class UserDataService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register(string $username, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        return $stmt->execute([$username, $hashedPassword]);
    }

    public function login(string $username, string $password): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && password_verify($password, $data['password'])) {
            return new User((int)$data['id'], $data['username'], UserRoleEnum::from($data['role']));
        }

        return null;
    }

    public function getRole(int $userId): ?string
    {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        return $stmt->fetchColumn() ?: null;
    }
}
<?php

declare(strict_types=1);

namespace App\DataServices;

use App\Config\Database;
use App\DTO\ReportStatsDTO;
use App\Enum\ActivitiesActionEnum;
use App\Enum\ActivitiesPageEnum;
use PDO;

class ActivityDataService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function log(int $userId, ActivitiesActionEnum $action, ?ActivitiesPageEnum $page = null): bool
    {
        $stmt = $this->db->prepare("INSERT INTO activities (user_id, action, page) VALUES (?, ?, ?)");

        return $stmt->execute([$userId, $action->value, $page?->value]);
    }

    public function getStats(array $filters = []): array
    {
        $query = "SELECT a.*, u.username FROM activities a JOIN users u ON a.user_id = u.id WHERE 1=1";
        $params = [];

        if (!empty($filters['date'])) {
            $query .= " AND DATE(a.created_at) = ?";
            $params[] = $filters['date'];
        }
        if (!empty($filters['user'])) {
            $query .= " AND u.username = ?";
            $params[] = $filters['user'];
        }
        if (!empty($filters['action'])) {
            $query .= " AND a.action = ?";
            $params[] = $filters['action'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * return ReportStatsDTO[]
     */
    public function getReportData(string $startDate, string $endDate): array
    {
        $startDate .= ' 00:00:00';
        $endDate .= ' 23:59:59';

        $query = "SELECT 
        DATE(created_at) as date,
        SUM(CASE WHEN action = 'view-page' AND page = 'A' THEN 1 ELSE 0 END) as page_a_views,
        SUM(CASE WHEN action = 'view-page' AND page = 'B' THEN 1 ELSE 0 END) as page_b_views,
        SUM(CASE WHEN action = 'button-click' AND page = 'A' THEN 1 ELSE 0 END) as buy_cow_clicks,
        SUM(CASE WHEN action = 'button-click' AND page = 'B' THEN 1 ELSE 0 END) as download_clicks
        FROM activities
        WHERE created_at BETWEEN ? AND ?
        GROUP BY DATE(created_at)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$startDate, $endDate]);

        $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new ReportStatsDTO(
            $row['date'],
            (int) $row['page_a_views'],
            (int) $row['page_b_views'],
            (int) $row['buy_cow_clicks'],
            (int) $row['download_clicks'],
        ), $raw);
    }
}
<?php

declare(strict_types=1);

namespace App\DTO;

class ReportStatsDTO
{
    public function __construct(
        public string $date,
        public int $pageAViews,
        public int $pageBViews,
        public int $buyCowClicks,
        public int $downloadClicks
    ) {}
}
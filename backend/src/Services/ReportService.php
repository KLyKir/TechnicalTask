<?php

declare(strict_types=1);

namespace App\Services;

class ReportService
{
    public function generateCsv(array $reports): string
    {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, [
            'Date',
            'Page View A',
            'Page View B',
            'Click "Buy a cow"',
            'Click "Download"',
        ]);

        foreach ($reports as $row) {
            fputcsv($handle, [
                $row->date ?? '',
                $row->pageAViews,
                $row->pageBViews ?? 0,
                $row->buyCowClicks ?? 0,
                $row->downloadClicks ?? 0,
            ]);
        }

        rewind($handle);
        return stream_get_contents($handle);
    }
}
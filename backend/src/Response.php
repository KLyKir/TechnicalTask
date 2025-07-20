<?php

declare(strict_types=1);

namespace App;

readonly class Response
{
    public function __construct(
        private string $content,
        private int    $status = 200,
        private array  $headers = ['Content-Type: application/json'],
    ) {}

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $header) {
            header($header);
        }
        echo $this->content;
    }

    public static function json(array $data, int $code = 200): self
    {
        return new self(json_encode($data), $code);
    }

    public static function error(string $message, int $code = 400): self
    {
        return self::json(['error' => $message], $code);
    }

    public static function success(array $data = [], int $code = 200): self
    {
        return self::json(array_merge(['success' => true], $data), $code);
    }

    public static function download(string $filePath, string $filename): self
    {
        if (!file_exists($filePath)) {
            return self::error('File not found', 404);
        }

        return new self(
            file_get_contents($filePath),
            200,
            [
                'Content-Type: application/octet-stream',
                "Content-Disposition: attachment; filename=\"$filename\"",
            ]
        );
    }

    public static function file(string $content, string $filename, string $contentType = 'application/octet-stream'): self
    {
        return new self($content, 200, [
            "Content-Type: $contentType",
            "Content-Disposition: attachment; filename=\"$filename\"",
        ]);
    }
}
<?php

declare(strict_types=1);

namespace App;

readonly class Request
{
    public function __construct(
        public string $method,
        public string $uri,
        private array $data
    ) {}

    public static function capture(): self
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?? [];

        return new self($method, $uri, $data);
    }

    public function input(string $key, $default = null): mixed
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    public function all(): array
    {
        return $this->data;
    }
}
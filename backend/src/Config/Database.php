<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private $host = 'db';
    private $db = 'activity_db';
    private $user = 'user';
    private $pass = 'secret';

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->db",
                $this->user,
                $this->pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
<?php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/routes/api.php';
<?php

declare(strict_types=1);

// Load Composer's autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load environment variables if .env file exists
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

// Set error reporting for development
if (($_SERVER['APP_ENV'] ?? 'dev') === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Set timezone
date_default_timezone_set('UTC');
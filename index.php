<?php
session_start();

// Include configuration and autoload
require_once "config/database.php";

// Basic routing
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require __DIR__ . '/views/home.php';
        break;
    case '/login':
        require __DIR__ . '/views/auth/login.php';
        break;
    case '/register':
        require __DIR__ . '/views/auth/register.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}

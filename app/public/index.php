<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/../patternRouter.php';
session_start();

$uri = trim($_SERVER['REQUEST_URI'], '/');

$router = new PatternRouter();

try {
    $router->route($uri);
} catch (Exception $e) {
    http_response_code(500);
    echo $e;
    die();
}
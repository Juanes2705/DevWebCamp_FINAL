<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Rutas disponibles
$routes = [
    '/api/eventos' => 'eventosAPI.php',
    '/api/ponentes' => 'ponenteAPI.php',
    '/api/regalos' => 'regalosAPI.php',
    '/api/registrados' => 'registradosAPI.php',
];

foreach ($routes as $route => $file) {
    if (strpos($requestUri, $route) === 0) {
        require_once __DIR__ . '/' . $file;
        exit;
    }
}

// Asegurar que el encabezado Content-Type sea JSON en caso de error
header('Content-Type: application/json');
http_response_code(404);
echo json_encode(['error' => 'Ruta no encontradaa']);

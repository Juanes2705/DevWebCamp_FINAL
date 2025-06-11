<?php
// public/api/ponentesAPI.php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/app.php'; // ajusta ruta según tu estructura

use Model\Ponente;

// Temporarily disable admin check for debugging
// if (!is_admin()) {
//     http_response_code(403);
//     echo json_encode(['error' => 'No autorizado']);
//     exit;
// }


// Obtener todos los ponentes
$ponentes = Ponente::all();

echo json_encode($ponentes);

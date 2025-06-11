<?php

use Model\Ponente;

require_once __DIR__ . '/../../includes/app.php'; // Ajusta según tu estructura

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Obtener ponentes desde la base de datos
$ponentes = Ponente::all();

// Preparar array para respuesta
$respuesta = [];

foreach($ponentes as $ponente) {
    $respuesta[] = [
        'id' => $ponente->id,
        'nombre' => $ponente->nombre,
        'ubicacion' => $ponente->ciudad . ', ' . $ponente->pais
    ];
}

// Enviar JSON
echo json_encode($respuesta);

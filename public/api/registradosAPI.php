<?php

require_once '../../includes/app.php';
use Model\Registro;
use Model\Paquete;
use Model\Usuario;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    $registrados = Registro::all();

    $registrados = array_map(function($registro) {
        $paquete = Paquete::find($registro->paquete_id);
        $usuario = Usuario::find($registro->usuario_id);

        $registro->paquete = $paquete ? $paquete->nombre : 'Sin paquete';
        $registro->nombre = $usuario ? $usuario->nombre : 'Sin nombre';
        $registro->apellido = $usuario ? $usuario->apellido : 'Sin apellido';
        $registro->email = $usuario ? $usuario->email : 'Sin email';

        return $registro;
    }, $registrados);

    echo json_encode($registrados);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

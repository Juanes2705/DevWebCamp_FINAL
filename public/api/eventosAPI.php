<?php

require_once '../../includes/app.php';
use Model\Evento;
use Model\Categoria;
use Model\Dia;
use Model\Hora;
use Model\Ponente;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Separar operaciones de lectura y escritura (CQRS)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $eventos = Evento::all();

        foreach ($eventos as $evento) {
            $categoria = Categoria::find($evento->categoria_id);
            $dia = Dia::find($evento->dia_id);
            $hora = Hora::find($evento->hora_id);
            $ponente = Ponente::find($evento->ponente_id);

            $evento->categoria = $categoria ? $categoria->nombre : 'Sin categoría';
            $evento->dia = $dia ? $dia->nombre : 'Sin día';
            $evento->hora = $hora ? $hora->hora : 'Sin hora';
            $evento->ponente = $ponente ? $ponente->nombre . ' ' . $ponente->apellido : 'Sin ponente';
        }

        echo json_encode($eventos);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['nombre']) || !isset($data['categoria_id'])) {
            throw new Exception('Datos incompletos para crear un evento.');
        }

        // Registrar el mensaje en la tabla de outbox
        $mensajeOutbox = [
            'topic' => 'eventos',
            'mensaje' => json_encode($data),
            'procesado' => 0,
            'fecha' => date('Y-m-d H:i:s')
        ];
        $db = ActiveRecord::getDB();
        $stmt = $db->prepare("INSERT INTO outbox (topic, mensaje, procesado, fecha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $mensajeOutbox['topic'], $mensajeOutbox['mensaje'], $mensajeOutbox['procesado'], $mensajeOutbox['fecha']);
        $stmt->execute();

        // Crear el evento
        $evento = new Evento($data);
        $resultado = $evento->guardar();

        echo json_encode(['success' => $resultado]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

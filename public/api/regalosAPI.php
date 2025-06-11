<?php

require_once '../../includes/app.php';
require_once __DIR__ . '/../../models/ActiveRecord.php';
use Model\Regalo;
use Model\Registro;
use Model\ActiveRecord;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    // Implementación alternativa para Windows (tabla temporal)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validar datos
        if (!isset($data['nombre'])) {
            throw new Exception('Datos incompletos para procesar el regalo.');
        }

        // Guardar en tabla temporal para procesamiento
        $db = ActiveRecord::getDB();
        $stmt = $db->prepare("INSERT INTO regalos_temporales (nombre, fecha) VALUES (?, ?)");
        
        // Asignar valores a variables antes de usar bind_param
        $nombre = $data['nombre'];
        $fecha = date('Y-m-d H:i:s');
        $stmt->bind_param("ss", $nombre, $fecha);
        $stmt->execute();

        // Respuesta inmediata
        echo json_encode(['success' => true, 'message' => 'El regalo se ha registrado para procesamiento.']);
    } else {
        $regalos = Regalo::all();

        if (empty($regalos)) {
            throw new Exception('No se encontraron regalos en la base de datos.');
        }

        $regalosSeleccionados = [];
        foreach($regalos as $regalo) {
            if (!isset($regalo->id) || !isset($regalo->nombre)) {
                throw new Exception('Propiedades id o nombre no definidas en el objeto regalo.');
            }

            $count = Registro::count(['regalo_id' => $regalo->id]);
            $regalosSeleccionados[] = [
                'nombre' => $regalo->nombre,
                'cantidad' => $count
            ];
        }

        echo json_encode($regalosSeleccionados);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

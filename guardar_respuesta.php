<?php
$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || !isset($datos['grupo']) || !isset($datos['alumno']) || !isset($datos['elecciones'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
    exit;
}

$grupo = preg_replace('/[^a-zA-Z0-9_]/', '_', $datos['grupo']); // seguridad
$archivo = "respuestas_{$grupo}.json";

if (file_exists($archivo)) {
    $respuestas = json_decode(file_get_contents($archivo), true);
    if (!is_array($respuestas)) {
        $respuestas = [];
    }
} else {
    $respuestas = [];
}

// Añadir nueva respuesta
$respuestas[] = [
    "alumno" => $datos['alumno'],
    "elecciones" => $datos['elecciones']
];

if (file_put_contents($archivo, json_encode($respuestas, JSON_PRETTY_PRINT))) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo guardar']);
}
?>

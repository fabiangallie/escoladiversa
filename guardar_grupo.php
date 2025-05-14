<?php
$archivo = 'grupos.json';

$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || !isset($datos['nombre']) || !isset($datos['alumnos'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos inválidos']);
    exit;
}

if (file_exists($archivo)) {
    $contenido = json_decode(file_get_contents($archivo), true);
    if (!is_array($contenido)) {
        $contenido = [];
    }
} else {
    $contenido = [];
}

$contenido[$datos['nombre']] = $datos['alumnos'];

if (file_put_contents($archivo, json_encode($contenido, JSON_PRETTY_PRINT))) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo guardar el grupo']);
}
?>

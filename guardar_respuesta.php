<?php
$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || !isset($datos['grupo']) || !isset($datos['alumno']) || !isset($datos['elecciones'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
    exit;
}

$grupo = preg_replace('/[^a-zA-Z0-9_]/', '_', $datos['grupo']); // seguridad
$nombreGrupoOriginal = $datos['grupo'];
$archivoRespuestas = "respuestas_{$grupo}.json";

// 1. Guardar respuestas individuales
if (file_exists($archivoRespuestas)) {
    $respuestas = json_decode(file_get_contents($archivoRespuestas), true);
    if (!is_array($respuestas)) $respuestas = [];
} else {
    $respuestas = [];
}

$respuestas[] = [
    "alumno" => $datos['alumno'],
    "elecciones" => $datos['elecciones']
];

if (!file_put_contents($archivoRespuestas, json_encode($respuestas, JSON_PRETTY_PRINT))) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo guardar']);
    exit;
}

// 2. Actualizar o crear index_resultados.json
$archivoIndice = "index_resultados.json";
$lista = [];

if (file_exists($archivoIndice)) {
    $lista = json_decode(file_get_contents($archivoIndice), true);
    if (!is_array($lista)) $lista = [];
}

// Evitar duplicados
$existe = false;
foreach ($lista as $item) {
    if ($item['archivo'] === $archivoRespuestas) {
        $existe = true;
        break;
    }
}
if (!$existe) {
    $lista[] = [
        "grupo" => $nombreGrupoOriginal,
        "archivo" => $archivoRespuestas
    ];
    file_put_contents($archivoIndice, json_encode($lista, JSON_PRETTY_PRINT));
}

echo json_encode(['status' => 'ok']);
?>

<?php
// Archivo donde se guardarán los grupos
$archivo = 'grupos.json';

// Obtener los datos enviados por POST (JSON)
$data = file_get_contents("php://input");
$nuevoGrupo = json_decode($data, true);

// Si el archivo ya existe, lo leemos. Si no, empezamos con un array vacío.
if (file_exists($archivo)) {
    $grupos = json_decode(file_get_contents($archivo), true);
} else {
    $grupos = [];
}

// Añadimos el nuevo grupo al array existente
$grupos[] = $nuevoGrupo;

// Guardamos el nuevo contenido en el archivo
file_put_contents($archivo, json_encode($grupos, JSON_PRETTY_PRINT));

// Devolvemos respuesta OK
header("Content-Type: application/json");
echo json_encode(["status" => "ok"]);
?>

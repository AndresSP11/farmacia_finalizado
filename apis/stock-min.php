<?php 

include_once '../config/database.php';

$db = conectarDB();

header('Content-Type: application/json'); // Establecer el tipo de contenido JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = isset($_POST['codigoPet']) ? $_POST['codigoPet'] : '';
    $stockMinimo = isset($_POST['stockMinimo']) ? $_POST['stockMinimo'] : '';

    // Validar los datos
    if (empty($codigo) || empty($stockMinimo)) {
        $response = ['valor' => false];
    } else {
        // Preparar la consulta
        $valor = explode('-', $codigo);
        $representa = $valor[0];

        $query = "UPDATE `petitorio` 
                  SET `minimo_stock` = ? 
                  WHERE `codigo_siga` = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('is', $stockMinimo, $representa);

        // Ejecutar la consulta
        $success = $stmt->execute();
        
        // Preparar la respuesta
        $response = ['valor' => $success];
    }

    // Codificar la respuesta en JSON
    try {
        echo json_encode($response, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo json_encode(['error' => 'Error al codificar JSON']);
    }
}
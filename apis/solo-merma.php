<?php 
include_once('../config/database.php');

$db=conectarDB();

header('Content-Type: application/json'); // Establecer el tipo de contenido JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dato = isset($_POST['valorInput']) ? mysqli_real_escape_string($db,$_POST['valorInput']) : '';
    $dato=mysqli_real_escape_string($db,$dato);
    $query = "SELECT * FROM `merma` WHERE id_medicamento LIKE '%$dato%';";
    $medicamentos = array();
    $ejecuta = mysqli_query($db, $query);
    
        while ($resultado = mysqli_fetch_assoc($ejecuta)) {
            
            $variable_1=$resultado['id_merma'];
            $variable_2=$resultado['cantidad'];
            $variable_3=$resultado['motivo'];
            $variable_4=$resultado['id_medicamento'];
            $variable_5=$resultado['lote'];
            
            $html.="
            <tr>
                <td>$variable_1</td>
                <td>$variable_4</td>
                <td>$variable_5</td>
                <td>$variable_2</td>
                <td>$variable_3</td>
            </tr>
            ";
        }
    /* echo json_encode($html); */
    $json_result = json_encode($html);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Error al codificar JSON']);
    } else {
        echo $json_result;
    }
}
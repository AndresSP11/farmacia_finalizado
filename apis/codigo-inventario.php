<?php
include_once('../config/database.php');
header("X-Frame-Options: DENY");
$db = conectarDB();

header('Content-Type: application/json'); // Establecer el tipo de contenido JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar la entrada
    $todo = isset($_POST['post']) ? mysqli_real_escape_string($db, $_POST['post']) : '';

    if ($todo == 'todo') {
        $query = "SELECT medicamento.id_medicamento,
                         medicamento.codigo_siga,
                         petitorio.denomin_comun_internac_o_principio_activo,
                         medicamento.cantidad,
                         medicamento.fecha_vencimiento,
                         medicamento.precio_unitaario, 
                         petitorio.minimo_stock
                  FROM medicamento
                  INNER JOIN petitorio
                  ON medicamento.codigo_siga = petitorio.codigo_siga;";

        // Ejecutar la consulta
        $ejecuta = mysqli_query($db, $query);

        if (!$ejecuta) {
            // Manejo de errores en la consulta SQL
            $error = mysqli_error($db);
            echo json_encode(['error' => $error]);
        } else {
            // Crear un array para almacenar los resultados
            $medicamentos = array(); 

            // Obtener resultados y agregarlos al array
            while ($resultado = mysqli_fetch_assoc($ejecuta)) {
                $medicamentos[] = $resultado;
            }   

            // Devolver el array como objeto JSON
            echo json_encode($medicamentos);
        }
    } else {
        // Si el parámetro post no es 'todo'
        echo json_encode(['error' => 'Parámetro incorrecto']);
    }
} else {
    // Si el método de solicitud no es POST
    echo json_encode(['error' => 'Método de solicitud incorrecto']);
}
?>
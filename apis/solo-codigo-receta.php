<?php 
include_once('../config/database.php');

$db = conectarDB();

header('Content-Type: application/json'); // Establecer el tipo de contenido JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dato = isset($_POST['post']) ? $_POST['post'] : '';
    
    $query = "SELECT 
                r.fichador_receta, 
                r.codigo_alumno, 
                r.dni_usuario, 
                r.dni_medico, 
                r.monto_total, 
                r.fecha, 
                m.nombre AS nombre_medico,
                u.nombre AS nombre_usuario
              FROM 
                receta r
              JOIN 
                médico m ON r.dni_medico = m.dni_medico
              JOIN
                usuario u ON r.dni_usuario = u.dni_usuario
              WHERE 
                r.codigo_alumno LIKE CONCAT(?, '%')";

    // Preparar la consulta
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $dato);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear la cabecera de la tabla
    $html = "<tr><th>NÚMERO DE <br> RECETA</th><th>CÓDIGO<br>ALUMNO</th><th>USUARIO</th><th>MEDICO</th><th>MONTO<br>TOTAL</th><th>FECHA DE <br>REGISTRO</th></tr>";

    while ($resultado = $result->fetch_assoc()) {
        $variable_1 = htmlspecialchars($resultado['fichador_receta'], ENT_QUOTES, 'UTF-8');
        $variable_2 = htmlspecialchars($resultado['codigo_alumno'], ENT_QUOTES, 'UTF-8');
        $variable_3 = htmlspecialchars($resultado['nombre_usuario'], ENT_QUOTES, 'UTF-8');
        $variable_4 = htmlspecialchars($resultado['nombre_medico'], ENT_QUOTES, 'UTF-8');
        $variable_5 = htmlspecialchars($resultado['monto_total'], ENT_QUOTES, 'UTF-8');
        $variable_6 = htmlspecialchars($resultado['fecha'], ENT_QUOTES, 'UTF-8');
        
        $html .= "<tr>
                    <td><a href='../pages/receta-id.php?id_receta=$variable_1' style='color:#195780'>$variable_1</a></td>
                    <td>$variable_2</td>
                    <td>$variable_3</td>
                    <td>$variable_4</td>
                    <td>$variable_5</td>
                    <td>$variable_6</td>       
                  </tr>";
    }

    try {
        $json_result = json_encode($html, JSON_THROW_ON_ERROR);
        echo $json_result;
    } catch (JsonException $e) {
        echo json_encode(['error' => 'Error al codificar JSON']);
    }
}
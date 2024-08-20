<?php 
include_once('../config/database.php');

$db = conectarDB();

header('Content-Type: application/json'); // Establecer el tipo de contenido JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dato = isset($_POST['post']) ? $_POST['post'] : '';

    // Preparar y ejecutar la consulta
    $query = "SELECT SUM(m.cantidad) AS total_cantidad,
                     m.codigo_siga, 
                     p.denomin_comun_internac_o_principio_activo,
                     p.minimo_stock,
                     p.forma_farmaceutica
              FROM medicamento m 
              INNER JOIN petitorio p ON m.codigo_siga = p.codigo_siga
              GROUP BY m.codigo_siga, p.denomin_comun_internac_o_principio_activo, p.minimo_stock, p.forma_farmaceutica
              HAVING p.denomin_comun_internac_o_principio_activo LIKE CONCAT(?, '%')";

    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $dato);
    $stmt->execute();
    $result = $stmt->get_result();

    $html = "<tr><th>CÓDIGO</th><th>MEDICAMENTO</th><th>FORMA FARMACÉUTICA</th><th>CANTIDAD</th><th>STOCK</th></tr>";

    while ($resultado = $result->fetch_assoc()) {
        $comida = '';
        if ($resultado['minimo_stock'] !== null) {
            if ($resultado['total_cantidad'] >= $resultado['minimo_stock']) {
                $comida = 'stock-positivo';
            } else {
                $comida = 'stock-negativo';
            }
        }

        $variable_1 = htmlspecialchars($resultado['codigo_siga'], ENT_QUOTES, 'UTF-8');
        $variable_2 = htmlspecialchars($resultado['denomin_comun_internac_o_principio_activo'], ENT_QUOTES, 'UTF-8');
        $variable_3 = htmlspecialchars($resultado['total_cantidad'], ENT_QUOTES, 'UTF-8');
        $variable_4 = htmlspecialchars($resultado['forma_farmaceutica'], ENT_QUOTES, 'UTF-8');

        $html .= "
            <tr>
                <td>$variable_1</td>
                <td>$variable_2</td>
                <td>$variable_4</td>
                <td>$variable_3</td>
                <td><span class='$comida'>Stock</span></td>
            </tr>";
    }

    try {
        $json_result = json_encode($html, JSON_THROW_ON_ERROR);
        echo $json_result;
    } catch (JsonException $e) {
        echo json_encode(['error' => 'Error al codificar JSON']);
    }
}
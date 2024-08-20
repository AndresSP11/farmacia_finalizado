<?php 

include_once '../config/database.php';

$db = conectarDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar el valor del POST
    $dato = mysqli_real_escape_string($db, $_POST['sol']);
   
    // Preparar la consulta SQL
    $stmt = $db->prepare("SELECT * FROM medicamento AS m 
                          INNER JOIN petitorio AS p 
                          ON m.codigo_siga = p.codigo_siga 
                          WHERE p.denomin_comun_internac_o_principio_activo LIKE CONCAT(?, '%')");
    $stmt->bind_param('s', $dato);
    $stmt->execute();
    $result = $stmt->get_result();

    $html = "";

    while ($ventas = $result->fetch_assoc()) {
        if ($ventas['cantidad'] !== 0) {
            $variable2 = htmlspecialchars($ventas['denomin_comun_internac_o_principio_activo'], ENT_QUOTES, 'UTF-8');
            $variable4 = htmlspecialchars($ventas['fecha_vencimiento'], ENT_QUOTES, 'UTF-8');
            $variable5 = htmlspecialchars($ventas['id_medicamento'], ENT_QUOTES, 'UTF-8');
            $variable6 = htmlspecialchars($ventas['numero_lote'], ENT_QUOTES, 'UTF-8');
            $variable7 = htmlspecialchars($ventas['cantidad'], ENT_QUOTES, 'UTF-8');
            $variable8 = htmlspecialchars($ventas['precio_unitaario'], ENT_QUOTES, 'UTF-8');
            $variable9 = htmlspecialchars($ventas['nro_imagen'], ENT_QUOTES, 'UTF-8');
            $variable10 = htmlspecialchars($ventas['codigo_siga'], ENT_QUOTES, 'UTF-8');

            $html .= "<div class='valor' id='$variable5'>
                        <p class='valorsito'>
                            $variable2 // ($variable4) // $variable5 // $variable6 // 
                            <br><span style='padding:2px; background:yellow;border-radius:10px;'>Cantidad: $variable7</span> // $variable8 // $variable4 // $variable9 // $variable10
                        </p>
                      </div>";
        }
    }

    $json_result = json_encode($html, JSON_THROW_ON_ERROR);
    echo $json_result;
}
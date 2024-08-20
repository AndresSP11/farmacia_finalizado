<?php 

include_once '../config/database.php';
session_start();
$nombre=$_SESSION['usuario'];
$documentoIde=$_SESSION['dni'];
$db=conectarDB();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = isset($_POST['codigo']) ? mysqli_real_escape_string($db, $_POST['codigo']) : '';
    $cantidad = isset($_POST['cantidad']) ? mysqli_real_escape_string($db, $_POST['cantidad']) : '';
    $tipoMerma = isset($_POST['tipoMerma']) ? mysqli_real_escape_string($db, $_POST['tipoMerma']) : '';
    $lote = isset($_POST['lote']) ? mysqli_real_escape_string($db, $_POST['lote']) : '';
    $fecha_actual = date("Y-m-d H:i:s");
    $codigo = isset($_POST['codigo']) ? mysqli_real_escape_string($db, $_POST['codigo']) : '';
    $codigo=str_replace(' ', '', $codigo);

    if (empty($codigo) || empty($cantidad) || empty($tipoMerma) || empty($lote)) {
        echo json_encode(['valor' => false, 'mensaje' => 'Todos los campos son obligatorios']);
    } else {
        // Insertar en la tabla 'merma'
        $stmt = $db->prepare("INSERT INTO `merma`(`cantidad`, `motivo`, `id_medicamento`, `lote`, `fecha_registro`, `dni_admin`, `nombre_admin`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issssss', $cantidad, $tipoMerma, $codigo, $lote, $fecha_actual, $documentoIde, $nombre);
        //'issssss': Especifica los tipos de datos de los parámetros que se van a vincular. Cada carácter en la cadena representa el tipo de uno de los parámetros
        
        if ($stmt->execute()) {
            // Verificar la cantidad de almacenamiento
            $stmt = $db->prepare("SELECT `cantidad` FROM `medicamento` WHERE `id_medicamento` = ?");
            $stmt->bind_param('s', $codigo);
            $stmt->execute();
            $result = $stmt->get_result();
            $venta = $result->fetch_assoc();

            if ($venta && $venta['cantidad'] >= $cantidad) {
                $variableResta = $venta['cantidad'] - $cantidad;

                // Actualizar la tabla 'medicamento'
                $stmt = $db->prepare("UPDATE `medicamento` SET `cantidad` = ? WHERE `id_medicamento` = ?");
                $stmt->bind_param('is', $variableResta, $codigo);

                if ($stmt->execute()) {
                    echo json_encode(['valor' => true, 'mensaje' => 'Enviado correctamente hacia el inventario']);
                } else {
                    echo json_encode(['valor' => false, 'mensaje' => 'Error al actualizar el inventario']);
                }
            } else {
                echo json_encode(['valor' => false, 'mensaje' => 'No se puede superar la cantidad, cantidad límite: ' . $venta['cantidad']]);
            }
        } else {
            echo json_encode(['valor' => false, 'mensaje' => 'Error al insertar en la tabla merma']);
        }
    }
}
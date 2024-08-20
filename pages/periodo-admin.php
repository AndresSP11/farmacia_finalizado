<?php

include '../config/database.php';

session_start();
$auth = $_SESSION['auth'];
$rol = $_SESSION['rol'];
if (!$auth) {
    header('Location: ../login.php');
    exit;
}
header("X-Frame-Options: DENY");
$db = conectarDB();

function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();

$alertas = [];

$anio = $_POST['anio'] ?? '';
$ciclo = $_POST['ciclo'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Limpieza de entradas
    $anio = mysqli_real_escape_string($db, $_POST['anio']);
    $ciclo = mysqli_real_escape_string($db, $_POST['ciclo']);

    // Validación de entradas
    if ($anio == '') {
        $alertas[] = 'Seleccione el año por favor';
    }
    if ($ciclo == '') {
        $alertas[] = 'Seleccione el periodo académico por favor';
    }

    if (empty($alertas)) {
        $concatenar = $anio . '-' . $ciclo;

        $query = "SELECT * FROM `periodo_academico`;";
        $ejecuta = mysqli_query($db, $query);

        if ($ejecuta->num_rows == 0) {
            $query = "INSERT INTO `periodo_academico`(`periodo`) VALUES ('$concatenar')";
            $ejecuta = mysqli_query($db, $query);
            
            if ($ejecuta) {
                $alertas[] = "Agregado correctamente";
            } else {
                $alertas[] = "Error al agregar el periodo";
            }
        } else {
            $query = "UPDATE `periodo_academico` SET `periodo` = '$concatenar'";
            $ejecuta = mysqli_query($db, $query);
            $queryUpdate="UPDATE aporte SET saldo=440";
            $ejecutar=mysqli_query($db,$queryUpdate);
            
            if ($ejecuta) {
                $alertas[] = "Periodo actualizado correctamente";
            } else {
                $alertas[] = "Error al actualizar el periodo";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title>Periodo Académico</title>
    <link rel="stylesheet" href="../styles/periodo-admin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>
<body>
    <div id="alertas-container">
        <?php 
        foreach($alertas as $alerta):
            $alertaClass = (strpos($alerta, 'actualizado correctamente') !== false) ? 'alerta success' : 'alerta error';
        ?>
        <div class="<?php echo $alertaClass; ?>"><?php echo $alerta; ?></div>
        <?php endforeach; ?>
    </div>
        <div class="container">
            <div class="portada-container">
                <div class="logo-uni">
                    <img src="/img/logo-uni.png" alt="">
                </div>
                <div class="photo-user" id="photo-user">
                    <img src="/img/user.png" alt="">
                    <div class="tooltip" id="tooltip">
                        <a href="#profile">Ver perfil</a>
                        <a href="#settings">Configuración</a>
                        <a href="../destruir-sesion.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>

            <div class="portada-providencial">
                    <h1>Periodo Acádemico</h1>
                    <div class="search-container-receta">
                        <form class="search" method="POST">
                        <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                            <div class="left-buscar">
                                <div class="input-buscar">
                                <label for="anio" class="search-text">Año</label>
                                <select name="anio" id="anio">
                                    <option value="">Seleccionar año:</option>
                                    <?php for ($year = 2024; $year <= 2047; $year++): ?>
                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                    <?php endfor; ?>
                                </select>
                                </div>
                                <div class="input-buscar">
                                <label for="ciclo" class="search-text">Período</label>
                                <select name="ciclo" id="ciclo">
                                    <option value="">Seleccionar período:</option>
                                    <option value="1">I</option>
                                    <option value="2">II</option>
                                </select>
                                </div>
                                <div class="contenedor-final">
                                    <div class="salir-receta">
                                    <a href="<?php if($rol==1){
                    echo('./new-stockmin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>"><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
                                    </div>
                                    <div class="submit-container">
                                    <button type="submit" class="submit-button">Aceptar Envío<i class='bx bxs-right-arrow'></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>

            <div class="mostrar-periodo">
                <p>
                    <?php
                        $query = "SELECT * FROM `periodo_academico`";
                        $ejecuta = mysqli_query($db, $query);
                        $resultado = mysqli_fetch_assoc($ejecuta);

                        if (isset($resultado['periodo'])) {
                            echo "Periodo Académico: " . $resultado['periodo'];
                        } else {
                            echo "Periodo Académico no disponible";
                        }
                    ?>
                </p>
            </div>
            <script nonce="<?php echo $nonce_value; ?>" src="../js/alerta.js"></script>
</body>
</html>

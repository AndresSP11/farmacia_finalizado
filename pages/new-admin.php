<?php
session_start();
$rol=$_SESSION['rol'];

if($rol!=='1'){
    header('Location: ./');
}
header("X-Frame-Options: DENY");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title>Document</title>
    <link rel="stylesheet" href="../styles/new-admin.css">
</head>
<body>
    <div class="portada-container">
        <div class="photo-user" id="photo-user">
            <img src="/img/user.png" alt="">
            <div class="tooltip" id="tooltip">
                <a href="#profile">Ver perfil</a>
                <a href="#settings">Configuración</a>
                <a href="../destruir-sesion.php">Cerrar sesión</a>
            </div>
        </div>
    </div>
    <section class="fondo-doctora">
        <div class="sidebar-val">
            <div>
                <img src="../imagenes/logouni.png" alt="" class="imagen">
            </div>
            <div>
                <a href="./visualizaciones.php" class="inventario-enlace">
                    <img src="../imagenes/azulmed.png" alt="">
                    <p>Inventario</p>
                </a>
                <a href="./entrada-stock.php" class="ingreso-med-enlace">
                    <img src="../imagenes/medicamentos.png" alt="">
                    <p>Ingreso de<br> medicamentos</p>
                </a>
                <a href="./new-nextpage.php" class="salida-med-enlace">
                    <img src="../imagenes/marron.png" alt="">
                    <p>Salida de<br>medicamentos</p>
                </a>
                <a href="./recetas-vista.php" class="salida-med-enlace">
                    <img src="../imagenes/gestion-recetas.png" alt="">
                    <p>Gestión de<br>recetas</p>
                </a>
                <a href="./new-stockmin.php" class="salida-med-enlace">
                    <img src="../imagenes/pastilla.png" alt="">
                    <p>Definición de<br>parámetros</p>
                </a>
            </div>
        </div>
    </section>
</body>
</html>
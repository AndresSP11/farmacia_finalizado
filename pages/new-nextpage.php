<?php

session_start();
$auth=$_SESSION['auth'];
if(!$auth){
    header('Location:../login.php');
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
    <link rel="stylesheet" href="../styles/new-nextpage.css">
    
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
                <a href="./recetario.php" class="inventario-enlace">
                    
                    <p>Registro de receta</p>
                </a>
                <a href="./salida-merma.php" class="ingreso-med-enlace">
                    
                    <p>Registro de merma</p>
                </a>
            </div>
        </div>
    </section>
</body>
</html>
<?php

session_start();
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];
if(!$auth){
    header('Location:../login.php');
}
if($rol!=1){
    header('Location:./inicio.php');
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
    <link rel="stylesheet" href="../styles/new-stockmin.css">
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
    <section class="fondo-doctora-1">
        <div class="sidebar-val">
            <div>
                <img src="../imagenes/logouni.png" alt="" class="imagen">
            </div>
            <div class="columns">
                <div>
                    <a href="./definir-min.php" class="inventario-enlace">
                        <img src="./imagenes/azulmed.png" alt="">
                        <p>Stock mínimo</p>
                    </a>
                    <a href="./periodo-admin.php" class="ingreso-med-enlace">
                        <img src="./imagenes/medicamentos.png" alt="">
                        <p>Periodo</p>
                    </a>
                    <a href="./petitoriomed-admin.php" class="ingreso-med-enlace">
                        <img src="./imagenes/medicamentos.png" alt="">
                        <p>Actualizar Petitorio</p>
                    </a>
                    <a href="./observarmerma.php" class="ingreso-med-enlace">
                        <img src="./imagenes/medicamentos.png" alt="">
                        <p>Visualizar <br>Mermas</p>
                    </a>
                </div>
                <div class="salir">
                    <a href="<?php if($rol==1){
                        echo('./new-admin.php');
                    }else{
                        echo('./new-inicio.php');
                    } ?>"><i class='bx bxs-left-arrow'></i>Regresar</a>
                </div>
            </div>
            
        </div>
    </section>
</body>
</html>
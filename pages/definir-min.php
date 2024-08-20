<?php 

include '../config/database.php';

session_start();
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];
if(!$auth){
    header('Location:../login.php');
}


$rol=$_SESSION['rol'];

if($rol!=1){
    header('Location:./inicio.php');
}
/*  */
header("X-Frame-Options: DENY");
$db=conectarDB();

$titulo='salida-merma';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    
    <title><?php echo $titulo ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/definir-min.css">
    <link rel="stylesheet" href="../styles/inventario.css">

</head>
<body>

    <div id="alertas-container">
        <div class="alertas"></div>
    </div>
    <div class="container-orden">

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

        <div class="portada-providencial-receta">
                    
                <h1>Gestión de Stock Mínimo</h1>
                <div class="search-container-receta">

                <div class="form-clas">
                    <div class="left">
                        <div class="div-1">
                        <label for="search-type" class="search-text-registrar" name="codigo">Código Petitorio:</label>
                        <input type="text" id="codigo-petitorio">
                        <div class="contenedor-divs"></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="div-2">
                        <label for="search-input" class="search-text-registrar" name="laboratorio">Stock-Mínimo:</label>
                        <input type="number" id="stock-minimo" min='0'>
                        </div>
                    </div>
                </div>     
            </div>
        </div>
        <div class="contenedor-final">
            <div class="salir-receta">
                <a href="<?php if($rol==1){
                    echo('./new-stockmin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>"><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
            </div>
            <div class="botones_siguiente">
            <button id="envio-form" class="envio-form"><i class='bx bxs-right-arrow'></i>Enviar</button>
            </div>
        </div> 
    </div>
    
    <script nonce="<?php echo $nonce_value; ?>" src="../js/stock-min.js"></script>
</body>
</html>
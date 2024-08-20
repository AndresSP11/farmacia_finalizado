<?php 
include '../config/database.php';
session_start();
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];
if(!$auth){
    header('Location:../login.php');
}
header("X-Frame-Options: DENY");
$db=conectarDB();
$titulo='Inventario';
$alertas=[];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    <title><?php echo $titulo ?></title>
    <link rel="stylesheet" href="../styles/inventario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
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
                <h1>Stock de medicamentos</h1>
                <div class="search-container">

                    <div class="search">
                        <div class="left">
                            <label for="search-type" class="search-text">Tipo de búsqueda:</label>
                            <select id="seleccion_tipo" name="search-type">
                                <option value="" disabled selected hidden>Seleccionar</option>
                                <option value="codigo-siga">Código de Farmacia</option>
                                <option value="medicamento">Medicamento</option>
                            </select>
                        </div>
                        
                        <div class="right">
                            <label for="search-input" class="search-text">Ingrese el dato:</label>
                            <input type="text" id="dato_texto" name="search-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tabla-container">
                <table class="tabla" id='tabla'>
                 <!-- Contenido de la tabla -->
                </table>
            </div>
            <div class="salir">
                <a href="
                <?php if($rol==1){
                    echo('./new-admin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>
                "><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
            <div class="">
                <a href="../excel-carpetas/excelinventario.php"><i class='bx bxs-notepad' ></i>Descargar Excel</a>
            </div>
            </div>
        </div>
        <script nonce="<?php echo $nonce_value; ?>" src="/boton.js"></script>   
        <script nonce="<?php echo $nonce_value; ?>" src="../js/inventario.js"></script>
</body>
</html>
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
$titulo='salida-merma';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title><?php echo $titulo ?></title>
    <link rel="stylesheet" href="../styles/salida-merma.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
<div class="container">
    <div id="alertas-container">
        <div class="alertas"></div>  
    </div>

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
            <h1>Salida por Merma</h1>          
            <div class="search-container">
            <div class="contenedor-merma">
                <table>
                    <tr>
                        <td class="azul"><label for="codigo-farmacia">MEDICAMENTO <br> INVENTARIO</label></td>
                        <td>
                            <input type="text" id="codigo-farmacia">
                            <div class="contenedor-divs"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="azul"><label for="nombre-medicamento">NOMBRE <br>MEDICAMENTO</label></td>
                        <td><input type="text" id="nombre-medicamento"></td>
                    </tr>
                    <tr>
                        <td class="azul"><label for="precio-unitario">PRECIO UNITARIO</label></td>
                        <td><input type="text" id="precio-unitario"></td>
                    </tr>
                    <tr>
                        <td class="azul"><label for="lote-medicamento">LOTE</label></td>
                        <td><input type="text" id="lote-medicamento"></td>
                    </tr>
                    <tr>
                        <td class="azul"><label for="fecha-vencimiento">FECHA DE<br> VENCIMIENTO</label></td>
                        <td><input type="text" id="fecha-vencimiento"></td>
                    </tr>
                </table>
                <div class="imagen">
                    <img src="" alt="Imagen de medicamento" id="imagen-merma">
                </div>
            </div>
            <div class="div_merma">
                <div class="div-1">
                <label for="cantidad-medicamento">Cantidad</label>
                <input type="text" id="cantidad-medicamento">
                
                </div>
                <div class="div-1">
                <label for="">Acciones</label>
                <select id="merma-medicamento">
                <option value="" selected disabled hidden>Selecciona una opción</option>
                <option value="Vencimiento">Vencimiento</option>
                <option value="Daño a la fabrica">Daño de la fábrica</option>
                <option value="Daño a la farmacia">Daño a la farmacia</option>
                <option value="Obsolencia">Obsolencia</option>
                <option value="Donación">Donación</option>
                </select>
                </div>
            </div>

            </div>
            </div>

            <div class="contenedor-final">
                <div class="salir-receta">
                    <a href="
                    <?php if($rol==1){
                    echo('./new-nextpage.php');
                }else{
                    echo('./new-inicio.php');
                } ?>
                    "><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
                </div>
                <div class="botones_siguiente">
                <button id="envio-form" class="envio-form"><i class='bx bxs-right-arrow'></i>Enviar</button>
                </div>
            </div> 

        </div>
    <script src="../js/salida-merma.js"></script>
</body>
</html>
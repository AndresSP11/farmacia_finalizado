<?php

session_start();
function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();
header("X-Frame-Options: DENY");
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];
if(!$auth){
    header('Location:../login.php');
}

/* Se Va obtener el id medicamento y esto se va registrar en base a ello  */
/* En este parte obtendermos lso valores correspondientes que estamos mandando para almacenar */

$fichador=htmlspecialchars($_GET['fichador']);
$dniMed=htmlspecialchars($_GET['dniMed']);
$fecha=htmlspecialchars($_GET['fecha']);
$codigoAlum=htmlspecialchars($_GET['codigoAlum']);

/* var_dump($_GET); */
if($fichador=='' || $dniMed=='' || $fecha=='' || $codigoAlum==''){
    header('Location: ./inicio.php');
}
/* if($_SESSION['alumno-pagado']==false){
    header('Location: ./inicio.php');
} */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title>Document</title>
    <link rel="stylesheet" href="../styles/salida-registrar.css">
    <link rel="stylesheet" href="../styles/inventario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div id="alertas-container">
        <div class="alertas"></div>
    </div>
    <div class="container-salida">

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
            <h1> SALIDA DE MEDICAMENTOS</h1>
            <div class="search-container-receta">

                <div>
                    <span class="styled-span">RECETA N° <?php echo $fichador ?></span>
                </div>

                <div class="span-underline"></div>

                <form class="formulario">
                <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                    <div class="formulario-search">
                        <div class="left">
                            <div class="div-1">
                            <label for="search-type" class="search-text">Medicamento:</label>
                            <input type="text" id="nombre-pet" name="sol">
                            <div class="contenedor-divs"></div>
                            </div>
                        </div>
                    
                        <div class="right">
                            <div class="div-2">
                            <label for="search-input" class="search-text">Cantidad:</label>
                            <input type="number" id="cantidadMed" min=0>
                            </div>
                        </div>
                    </div>
                    <div class="div-3">
                        <button type="submit" id="salidaReg">Agregar</button>
                    </div>
                </form>
            </div>
        </div>


    <div class="tabla-container-receta">
        <table class="tabla" id='tabla'>
            <tr>
                <th>CÓDIGO INV</th>
                <th>CÓDIGO SIGA</th>
                <th>MEDICAMENTO</th>
                <th>CANTIDAD</th>
                <th>COSTO UNITARIO</th>
                <th>COSTO TOTAL</th>
                <th>ELIMINAR</th>

            </tr>
        </table>
    </div>

    <div class="monto-total-dinero">
        <p>Monto Total:</p>
        <span><p class="parrafo"></p></span>
    </div>

    <div class="contenedor-final">
        <div class="salir-receta">
            <a href="<?php if($rol==1){
                    echo('./new-admin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>"><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
        </div>
        <div class="botones_siguiente">
        <button id="enviar-ls-md" class="envio-form"><i class='bx bxs-right-arrow'></i>Enviar</button>
        </div>
    </div> 

    </div>
    <script nonce="<?php echo $nonce_value; ?>" src="../js/salida-registrar.js"></script>

</body>
</html>
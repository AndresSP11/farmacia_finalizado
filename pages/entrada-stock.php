<?php 

include '../config/database.php';

session_start();
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];
if(!$auth){
    header('Location:../login.php');
    exit();  //agregado
}

$anioActual=date('Y');
/* ClickHacking */
header("X-Frame-Options: DENY");
$db=conectarDB();

function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();


$titulo='Registra el codigo Orden';

$alertas=[];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $codigo=mysqli_real_escape_string($db,$_POST['codigo-orden']);
    $fecha=mysqli_real_escape_string($db,$_POST['fecha_llegada']);
    $proveedor=mysqli_real_escape_string($db,$_POST['proveedor']);
    $codigo=$codigo.$anioActual;
    if(!$codigo){
        $alertas[]="Introducir la orden de compra";
    }
    if(!$fecha){
        $alertas[]="Introducir una fecha";
    }
    if(!$proveedor){
        $alertas[]="Introducir el nombre del proveedor";
    }
    if(empty($alertas)){

        $query="SELECT * FROM orden_de_compra WHERE id_orden='$codigo'";
        $ejecuta=mysqli_query($db,$query);

        if($ejecuta->num_rows){
            $alertas[]="Ya existe el codigo de Orden asi que no será posible enviarlo";
        }else{
            header('Location: ./registrar-orden.php?codigo-orden='.$codigo.'&fecha-llegada='.$fecha.'&proveedor='.$proveedor);
        }

        
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    
    <title><?php echo $titulo ?></title>
    <link rel="stylesheet" href="../styles/inventario.css">
    <link rel="stylesheet" href="../styles/entrada-stock.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Contenedor de alertas -->
    <div id="alertas-container">
        <?php 
            foreach($alertas as $alerta):?>
            <div class="alerta error"> <?php  echo $alerta ;?></div>
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
                <h1>Ingreso de medicamentos</h1>
                <div class="search-container-receta">

                    <form class="search" method="POST">
                    <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                        <div class="left-buscar">
                            <div class="input-buscar">
                            <label for="codigo-orden" class="search-text">Orden de Compra N°:</label>
                            <input type="text" id="codigo_orden" name="codigo-orden">
                            </div>
                            <div class="input-buscar">
                            <label for="fecha-llegada" class="search-text">Fecha:</label>
                            <input type="date" id="fecha_llegada" name="fecha_llegada"> 
                            </div>
                            <div class="input-buscar">
                            <label for="proveedor" class="search-text">Proveedor:</label>
                            <input type="text" id="proveedor" name="proveedor"> 
                            </div>
                            <div class="contenedor-final">
                                <div class="salir-receta">
                                 <a href="<?php if($rol==1){
                    echo('./new-admin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>"><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
                                </div>
                                <div>
                                    <a href="../excel-carpetas/ordenCompra.php" class="dowmload-excel">
                                        <i class='bx bxs-file-export'></i>
                                        Descargar Excel
                                    </a>
                                </div>

                                <div class="submit-container">
                                <button type="submit" class="submit-button">Siguiente<i class='bx bxs-right-arrow'></i></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

        <script nonce="<?php echo $nonce_value; ?>" src="/boton.js"></script>
        <script nonce="<?php echo $nonce_value; ?>" src="../js/inventario.js"></script>
        <script nonce="<?php echo $nonce_value; ?>" src="../js/alerta.js"></script>
        <script nonce="<?php echo $nonce_value; ?>" src="../js/entrada-stock.js"></script>


</body>
</html>
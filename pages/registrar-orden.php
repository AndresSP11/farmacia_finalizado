<?php 

include_once('../config/database.php');
header("X-Frame-Options: DENY");
$db=conectarDB();

session_start();
function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();

$auth=$_SESSION['auth'];
if(!$auth){
    header('Location:../login.php');
    exit; //agregado
}

$codigo = isset($_GET['codigo-orden']);
$fechaLlegada = isset($_GET['fecha-llegada']);
$proveedor = isset($_GET['proveedor']);

$query="SELECT * FROM orden_de_compra WHERE id_orden='$codigo'";
$ejecuta=mysqli_query($db,$query);


if($codigo=='' || $fechaLlegada=='' || $proveedor==''){
    header('Location: ./entrada-stock.php');
}

if($ejecuta->num_rows){
    header('Location: ./entrada-stock.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <link rel="stylesheet" href="../styles/registrar-orden.css">
    <link rel="stylesheet" href="../styles/inventario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>
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
                        
                    <h1></h1>
                    <div class="search-container-receta">

                    <div>
                        <span class="styled-span">Orden de Compra N° <?php echo $_GET['codigo-orden'] ?></span>
                    </div>
                    <div class="span-underline"></div>
                   
                    <form  class="form-clas" method="post">
                    <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                        <div class="left">
                            <div class="div-1">
                            <label for="search-type" class="search-text-registrar" name="codigo">Medicamento:</label>
                            <input type="text" id="codigo-pet">
                            <div class="contenedor-divs"></div>
                            </div>
                            <div class="div-1">
                            <label for="search-type" class="search-text-registrar" name="pre-unitario">Precio unitario:</label>
                            <input type="text" id="preciou-pet"> 
                            </div>
                            <div class="div-1">
                            <label for="search-type" class="search-text-registrar" name="fecha-vencimiento">Vencimiento:</label>
                            <input type="date" id="fechav-pet"> 
                            </div>
            
                        </div>
                        
                        <div class="right">
                            <div class="div-2">
                            <label for="search-input" class="search-text-registrar" name="laboratorio">Laboratorio:</label>
                            <input type="text" id="laboratorio-pet">
                            </div>
                            <div class="div-2">
                            <label for="search-input" class="search-text-registrar" name="pre-unitario">Lote:</label>
                            <input type="text" id="number-lote">
                            </div>
                            <div class="div-2">
                            <label for="search-input" class="search-text-registrar" name="pre-unitario">Cantidad:</label>
                            <input type="text" id="cantidad-pet">
                            </div>    
                        </div>
                        <div class="div-3">
                                <input type="submit"
                                value="Agregar"
                                id="enviar-medicamentos"
                                class='enviar-medicamentos'>
                        </div>   
                    </form>
                </div>
            </div>

            <div class="table-ls">
                    <table class="tabla" id='tabla'>
                        <tr>
                            <th>CODIGO PETITORIO</th>
                            <th>CÓDIGO SIGEMID</th>
                            <th>MEDICAMENTO</th>
                            <th>COSTO UNITARIO</th>
                            <th>LOTE</th>
                            <th>CANTIDAD</th>
                            <th>LABORATORIO</th>
                            <th>FECHA DE VENCIMIENTO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </table>
            </div>

            <div class="contenedor-mt">
                    <p>Monto total:</p>
                    <span><p class="monto-total-dinero"></p></span>
            </div>
                

            <div class="cont-salir-receta">
                    <div class="salir-receta">
                    <a href="./entrada-stock.php" class="ajuste-1"><i class='bx bxs-left-arrow'></i>Volver atrás</a>
                    <button id="enviar-ls-md" class="ajuste-2">Guardar<i class='bx bxs-right-arrow'></i></button> 
                    </div>
            
            </div>
        </div>

        <script nonce="<?php echo $nonce_value; ?>" src="../js/registro-orden.js"></script>

</body>
</html>
<?php 

include '../config/database.php';

session_start();
$auth=$_SESSION['auth'];
if(!$auth){
    header('Location:../login.php');
}


header("X-Frame-Options: DENY");
$db=conectarDB();

$titulo='Inicio';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title><?php echo $titulo ?></title>
    <link rel="stylesheet" href="../styles/sidebar.css">
    <link rel="stylesheet" href="../styles/entrada-stock.css">
    <link rel="stylesheet" href="../styles/side-izq.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
        <div class="dashboard">
        <aside class="aside">
                <div class="titulo-cm-bg">
                    <img src="../img/logo-uni.png" alt="">
                    <h2 class="blue">Centro <span class="green">Medico</span> </h2>
                </div>
                
                <nav class="sidebar-nav">
                <a href="./inicio.php" class=<?php if($titulo=='Inicio'){ echo 'pintado'; }?>>Inicio</a>
                <a href="./entrada-stock.php">Entrada Stock</a>
                <div class="padre-links"> 
                    <div class="title-links">
                        <h1>Salida de medicamento</h1>
                        <i class='bx bx-chevron-right'></i>
                    </div>

                    <div class="show-links">
                        <a href="./recetario.php">Por Receta</a>

                        <a href="./salida-merma.php">Por Vencimiento</a>
                        
                    </div>
                </div> 
                <a href="./inventario.php">Inventario</a>
                <a href="./recetas-vista.php">Recetas Gestionadas</a>
                <?php if($_SESSION['rol']==1):?>
                    <a href="./definir-min.php">Admin-Def.StockMin</a>
                <?php endif; ?>

                <a href="../destruir-sesion.php">Cerrar Sesión</a>
                </nav>
        </aside>
            <div class="principal">
                <div class="barra">
                    <h1>Historia de Centro Medico</h1>
                </div>
                <div class="contenido">
                    <div class="contenido-imagen-centro">
                        <h2>Inaguración del Centro Medico-Imagen presente del Rector</h2>
                        <img src="../img/centro-medico-inicio.jpeg" alt="">
                    </div>
                
                </div>
            </div>
        </div>
        <script nonce="<?php echo $nonce_value; ?>" src="../js/mostrando-menu.js"></script>
        
</body>
</html>
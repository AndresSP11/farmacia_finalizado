<?php 

include_once('../config/database.php');

header("X-Frame-Options: DENY");
$db=conectarDB();

session_start();
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];

if(!$auth){
    header('Location:../login.php');
}
if($rol==0){
    header('Location:../login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title>Document</title>
    <link rel="stylesheet" href="../styles/observarmerma.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container-todo">
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
        <div class="container">
            <div class="portada-providencial">
                <h1>Registro de Mermas</h1>
            </div>
        </div>
       
        <div class="input-merma">
            <label class="search-text">Código de Merma:</label>
            <input type="text" class="input-delamerma">
        </div>

        <div class="container">
            <div class="tabla-container">
                <table class="tabla" id='tabla'>
                        <thead>
                        <tr>
                        <th>CÓDIGO <br> MERMA</th>
                        <th>MEDICAMENTO</th>
                        <th>LOTE</th>
                        <th>CANTIDAD</th>
                        <th>MOTIVO</th>
                        </tr>
                        </thead>
                <tbody class="proceso-get">
                    <?php

                        $query="SELECT * FROM `merma`;";
                        $ejecutar=mysqli_query($db,$query);
                    ?>
                    <?php
                    while($elemento=mysqli_fetch_assoc($ejecutar)):?>
                        <tr>
                            <td><?php echo $elemento['id_merma'];?></td>
                            <td><?php echo $elemento['id_medicamento'];?></td>
                            <td><?php echo $elemento['lote'];?></td>
                            <td><?php echo $elemento['cantidad'];?></td>
                            <td><?php echo $elemento['motivo'];?></td>
                        </tr>
                    <?php endwhile;?>
                </tbody>
                </table>
            </div>
        </div>  
        <div class="salir">
                <a href="<?php if($rol==1){
                    echo('./visualizaciones.php');
                }else{
                    echo('./new-inicio.php');
                } ?>"><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
                <div>
                    <a href="../excel-carpetas/excelmerma.php"><i class='bx bxs-notepad' ></i>Descargar el Excel</a>
                </div>
                
        </div>
    </div>
    <script nonce="<?php echo $nonce_value; ?>" src="../js/observarmerma.js"></script>
</body>
</html>
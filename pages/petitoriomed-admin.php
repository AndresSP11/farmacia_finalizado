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

function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();

$alertas=[];

$codigo=$_POST['codigo-siga'] ?? '';

$concentracion= '' ?? $_POST['concentracion'];



if($_SERVER['REQUEST_METHOD']=='POST'){ 
    $codigo=mysqli_escape_string($db,$_POST['codigo-siga']);
    $concentracion=mysqli_escape_string($db,$_POST['concentracion']);
    $nombreMedicamento=mysqli_escape_string($db,$_POST['nombre-medicamento']);
    $formaFarma=mysqli_escape_string($db,$_POST['forma-farma']);
    $minStock=mysqli_escape_string($db,$_POST['minimo-stock']) ?? ' ';

    if($codigo==''){
        $alertas[]='No puede ir vacio el codigo';
    }else{
        if(strlen($codigo)!=12){
            $alertas[]='El codigo tiene que tener 12 digitos';
        }
    }
    if($concentracion==''){
        $alertas[]='No puede ir vacio la concentración';
    }
    if($nombreMedicamento==''){
        $alertas[]='El nombre no puede ir vacio';
    }
    if($formaFarma==''){   
        $alertas[]='La forma Farmeceutica no puede ir vacio';
    }
    if($minStock!=''){
        if (!ctype_digit($minStock)) {
            $alertas[] = 'Solo se ingresa números en mínimo stock';
        }
    }
    if(empty($alertas)){
        
        $query="SELECT * FROM `petitorio` WHERE codigo_siga='$codigo';";
        
        $ejecuta=mysqli_query($db,$query);

        if($ejecuta->num_rows!=0){
            $alertas[]="Este medicamento ya se encuentra en la lista, porfavor revisar la lista";
        }else{
            /* Aqui se va introducir los elementos que se 
            esta mandando en la lista despues de validarlo */
            $query="INSERT INTO `petitorio`(`codigo_siga`, `denomin_comun_internac_o_principio_activo`, `concentracion`, `forma_farmaceutica`, `minimo_stock`)
            VALUES ('$codigo','$nombreMedicamento','$concentracion','$formaFarma','$minStock');";
            $ejecuta=mysqli_query($db,$query);
            $alertas[]="Agregado Correctamente";

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
    <title>Document</title>
    <link rel="stylesheet" href="../styles/petitoriomed-admin.css">
    <link rel="stylesheet" href="../styles/observarmerma.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>
<body>
    <div id="alertas-container">
        <?php 
            foreach($alertas as $error):?>
            <div class="alerta error"> <?php  echo $error ;?></div>
        <?php endforeach; ?>
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
    <div class="container">
        <div class="portada-providencial-receta">  
            <h1>Petitorio</h1>
            <div class="search-container-receta">
                <form  class="form-clas" method='post'>
                <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                    <div class="left">
                        <div class="div-1">
                        <label for="search-type" class="search-text-registrar" name="codigo">Código SIGA:</label>
                        <input type="text" id="codigo-pet" name="codigo-siga">
                        </div>
                        <div class="div-1">
                        <label for="search-type" class="search-text-registrar" name="pre-unitario">Concentración:</label>
                        <input type="text" id="preciou-pet" name="concentracion"> 
                        </div>
                        <div class="div-1">
                        <label for="search-type" class="search-text-registrar" name="fecha-vencimiento">Medicamento:</label>
                        <input type="text" id="fechav-pet" name="nombre-medicamento"> 
                        </div>

                    </div>
                    
                    <div class="right">
                        <div class="div-2">
                        <label for="search-input" class="search-text-registrar" name="laboratorio">Forma Farmaceutica:</label>
                        <input type="text" id="laboratorio-pet" name="forma-farma">
                        </div>
                        <div class="div-2">
                        <label for="search-input" class="search-text-registrar" name="pre-unitario">Mínimo Stock:</label>
                        <input type="text" id="number-lote" name="minimo-stock">
                        </div>  
                        

                    </div>
                    <div class="div-3">
                        <button type="submit" id="enviar-medicamentos" class="enviar-medicamentos">Agregar</button>
                    </div>   
                </form>
            </div>
        </div>
        <div class="container">
            <div class="tabla-container-petitorio">
                <table class="tabla" id='tabla'>
                <thead>
                    <tr>
                    <th>CÓDIGO <br> SIGA</th>
                    <th>MEDICAMENTO</th>
                    <th>CONCENTRACIÓN</th>
                    <th>FORMA FARMACEUTICA</th>
                    <th>MINÍMO STOCK</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $query="SELECT * FROM `petitorio`;";
                    $ejecutar=mysqli_query($db,$query);
                ?>
                <?php
                while($elemento=mysqli_fetch_assoc($ejecutar)):?>
                <tr>
                    <td><?php echo $elemento['codigo_siga'];?></td>
                    <td><?php echo $elemento['denomin_comun_internac_o_principio_activo'];?></td>
                    <td><?php echo $elemento['concentracion'];?></td>
                    <td><?php echo $elemento['forma_farmaceutica'];?></td>
                    <td><?php echo $elemento['minimo_stock'];?></td>
                </tr>
                <?php endwhile;?>
                </tbody>
                </table>
            </div> 
        </div>
        <div class="salir">
                <a href='<?php if($rol==1){
                    echo('./new-admin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>'><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
        </div>
    </div>
    
    <script nonce="<?php echo $nonce_value; ?>" src="../js/alertapetitorio.js">
    </script>


</body>
</html>
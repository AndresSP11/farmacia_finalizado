<?php 

include '../config/database.php';

session_start();
$auth=$_SESSION['auth'];
if(!$auth){
    header('Location:../login.php');
}

header("X-Frame-Options: DENY");
$db=conectarDB();

$titulo='Inventario';

$alertas=[];

/* Aqui vamos a gestionar la parte de id_receta, con un GET de esta forma obtendremos
los medicamentos que tengan un mismo id, en base a Y QUE ME PRESENTE LAS CANTIDADES CORRESPONIENTE 
mediante el innerJoin solicitar los precios, cantidad,  */

$id=mysqli_real_escape_string($db,$_GET['id_receta']);
$id=htmlspecialchars($id);

$query="SELECT * FROM receta WHERE fichador_receta='$id'";

$resultado=mysqli_query($db,$query);

if($id){
    if($resultado->num_rows){
        
    }else{
        header('Location: ./inicio.php');
    }
}else{
    header('Location: ./inicio.php');
}

  // Supongo que la conexión a la base de datos está almacenada en $db
  $query = "SELECT fecha, codigo_alumno, dni_medico FROM `receta` WHERE fichador_receta='$id';";
  $ejecuta = mysqli_query($db, $query);
  $receta = mysqli_fetch_assoc($ejecuta);

  $codigo_alumno = $receta['codigo_alumno'];
  $dni_medico = $receta['dni_medico'];

  // Obtener el nombre del alumno
  $query_alumno = "SELECT nombre_alumno FROM `alumno` WHERE codigo_alumno='$codigo_alumno';";
  $ejecuta_alumno = mysqli_query($db, $query_alumno);
  $alumno = mysqli_fetch_assoc($ejecuta_alumno);
  $nombre_alumno = $alumno['nombre_alumno'];

  // Obtener el nombre del médico
  $query_medico = "SELECT nombre FROM `médico` WHERE dni_medico='$dni_medico';";
  $ejecuta_medico = mysqli_query($db, $query_medico);
  $medico = mysqli_fetch_assoc($ejecuta_medico);
  $nombre_medico = $medico['nombre'];
  // Obtener el Nombre del Tecnico que atendió
  $tecnico="SELECT u.nombre FROM receta r INNER JOIN usuario u ON r.dni_usuario = u.dni_usuario WHERE r.fichador_receta=$id;";
  $ejecutaTecnico=mysqli_query($db,$tecnico);
  $resultadoTecnico=mysqli_fetch_assoc($ejecutaTecnico); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title><?php echo $titulo ?></title>
    <link rel="stylesheet" href="../styles/inventario.css">
    <link rel="stylesheet" href="../styles/receta-id.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/contenedor-botones.css">
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
                    <h1></h1>
                    <div class="search-container-receta">

                        <div>
                            <span class="styled-span">RECETA N° <?php echo $id ?></span>
                        </div>

                        <div class="span-underline"></div>
                        <div class="search-receta">
                            <div class="left">
                                <div class="div-1">
                                <label for="search-type" class="search-text">Alumno:</label>
                                <input type="text" id="dato_texto_div1" value="<?php echo $nombre_alumno ?>" readonly>
                                </div>
                                <div class="div-1">
                                <label for="search-type" class="search-text">Médico:</label>
                                <input type="text" id="dato_texto_div1" value="<?php echo $nombre_medico; ?>" readonly> 
                                </div>
                
                            </div>
                            
                            <div class="right">
                                <div class="div-2">
                                <label for="search-input" class="search-text">Código UNI:</label>
                                <input type="text" id="dato_texto_div2" value="<?php echo $codigo_alumno; ?>" readonly>
                                </div>
                                <div class="div-2">
                                <label for="search-input" class="search-text">Técnico Farmacia:</label>
                                <input type="text" id="dato_texto_div2" value="<?php echo $resultadoTecnico['nombre'];?>" readonly>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

           
            <div class="tabla-container-receta">
                <table class="tabla" id='tabla'>
                <tr>
                    <th>CÓDIGO ATC</th>
                    <th>MEDICAMENTO</th>
                    <th>CONCENTRACIÓN</th>
                    <th>CANTIDAD</th>
                    <th>PRESENTACIÓN</th>
                    <th>COSTO UNITARIO</th>
                    <th>COSTO TOTAL</th>
                </tr>
                    <!-- Aqui esta la parte de generación de columnas -->
                    <?php  
                    $query="SELECT medicamento_receta.id_medicamento AS id_medicamento,medicamento_receta.cantidad AS cantidad_receta_cm, medicamento.*, petitorio.*
                    FROM medicamento_receta
                    JOIN medicamento ON medicamento_receta.id_medicamento = medicamento.id_medicamento
                    JOIN petitorio ON medicamento.codigo_siga = petitorio.codigo_siga WHERE medicamento_receta.fichador_receta='$id';";
                    $ejecuta=mysqli_query($db,$query);

                    $sumador = 0; // Inicializar la variable 

                    ?>
                    <?php while($producto=mysqli_fetch_assoc($ejecuta)):
                        
                        $sumador=$producto['cantidad_receta_cm']*$producto['precio_unitaario']+$sumador;

                    ?>
                <tr>
                    <td><?php echo $producto['id_medicamento']?></td>
                    <td><?php echo $producto['denomin_comun_internac_o_principio_activo']?></td>
                    <td><?php echo $producto['concentracion']?></td>
                    <td><?php echo $producto['cantidad_receta_cm']?></dh>
                    <td><?php echo $producto['forma_farmaceutica']?></dh>
                    <td><?php echo "S/". $producto['precio_unitaario']?></td>
                    <td><?php echo "S/". $producto['cantidad_receta_cm']*$producto['precio_unitaario']?></td>
                </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <div class="monto-total">
                <p>Monto Total:</p>
                <span><p class="parrafo"><?php echo "S/".$sumador?></p></span>
            </div>
            

            <div class="salir-receta">
                <a href="./recetas-vista.php"><i class='bx bxs-left-arrow'></i>Retroceder</a>
            </div>

        </div>

        <script nonce="<?php echo $nonce_value; ?>" src="/boton.js"></script>
</body>
</html>
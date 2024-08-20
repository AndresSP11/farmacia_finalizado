<?php 

include '../config/database.php';
include '../pruebas/nusoap.php';

session_start();

function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();
$auth=$_SESSION['auth'];
$rol=$_SESSION['rol'];
if(!$auth){
    header('Location:../login.php');
}

$vendedorId = "";

header("X-Frame-Options: DENY");
$db=conectarDB();

$titulo='salida-merma';
/* ####################################### */

$periodoquery="SELECT * FROM periodo_academico;";
$ejecutaQuery=mysqli_query($db,$periodoquery);

$consola=mysqli_fetch_assoc($ejecutaQuery);

$periodoDeterminado=$consola['periodo'];

$separaciónDeParametros=explode('-',$periodoDeterminado);
$todoJunto=$separaciónDeParametros['0'].$separaciónDeParametros['1'];
/*  */

/* Aqui se van a seguir 2 procedimientos */

$errores=[];

/*  Aqui por ahora solo mandaremos los datos correspondientes con el header
location pero conlos valroes get introducidos, osea con variable GET en la URL
  */
  $query="SELECT * FROM médico";
/*  Aqui por ahora solo mandaremos los datos correspondientes con el header
location pero conlos valroes get introducidos, osea con variable GET en la URL
  */
$ejecuta_consulta=mysqli_query($db,$query);

/* De hecho esto sucedera si es que al momento de mandar el POST, se vea en la consulta
si existe una relación o no con los valores POST */
if($_SERVER['REQUEST_METHOD']=='POST'){

    $fichador=mysqli_real_escape_string($db,$_POST['fichador']);
    $dniMed=mysqli_real_escape_string($db,$_POST['dni-medico']);
    $fecha=mysqli_real_escape_string($db,$_POST['fecha']);
    $codigoAlum=mysqli_real_escape_string($db,$_POST['codigo-alumno']);
    $codigoAlum=trim($codigoAlum);

    $query="SELECT * FROM receta WHERE fichador_receta='$fichador'";

    $ejecuta=mysqli_query($db,$query);

    
    if($ejecuta->num_rows==1){
        $errores[]="Este codigo de fichador ya existe";
    }
    if(!$fichador){
        $errores[]="Es necesario introducir el codigo de farmacia";
    }
    if($fichador<0){
        $errores[]="El fichador no puede ser un valor negativo";
    }
    if(!$dniMed){
        $errores[]="Es necesario seleccionar a uno de los medicos";
    }
    if(!$fecha){
        $errores[]="Es necesario elegir la fecha";
    }
    if(!$codigoAlum){
        $errores[]="Es necesario colocar el codigo del alumno";
    }
    if(empty($errores)){
        $client = new nusoap_client('https://wsorce.uni.edu.pe/OTI/wsProyectoCentroMedico.php');
        session_start();
        // $_SESSION['tokenWebservice'] = "";
        // Crear una SESSION para guardar el token
        
        /* EN LA PARTE DE SESSION CON TOKEN */

        if (!isset($_SESSION["tokenWebservice"]) || $_SESSION["tokenWebservice"]=="") {
            // print_r("-----------");exit;
            $usuario = "Ot1_uN1";
            $clave = "/yGge#";
            // Funcion que genera el token. Datos necesarios: usuario y clave 
            $resultToken = $client->call('wsProyectoCentroMedico.getAuthenticate', ['usuario'=>$usuario,'clave'=>$clave]);
            
            if ($client->fault) {
                print_r($resultToken['faultstring']);
            } else {
                $error = $client->getError();
                if ($error) {
                    // Mostrar errores
                    echo "<h2>Error</h2><pre>" . $error . "</pre>";
                } else {
                    // respuesta de la webservice
                    $response = json_decode($resultToken);
                    if ($response->status=="success") {
                        // Almacenar el token en la SESSION del servidor
                        // print_r($response);exit;
                        $_SESSION['tokenWebservice'] = $response->token;
                    }else{
                        // Mostrar errores detallados
                        // print_r($resultToken);exit;
                        print_r($response);exit;
                        $errores[]="Codigo mal registrado o Alumno inexistente";
                    }
                }
            }
        }

        /* AQUI LOS VALORES CON RESULTADOS  */

        if (isset($_SESSION["tokenWebservice"]) && $_SESSION["tokenWebservice"]!="") {
            // $codigoAlumno = "19850373K";
            $codigoAlumno = "$codigoAlum";
            // codigo 20160311I
            // dni 76616621
            // flag
            $token = $_SESSION['tokenWebservice'];
            // Funcion para mostrar alumnos matriculados
            $result = $client->call('wsProyectoCentroMedico.getDatosAlumno', ['token'=>$token,'codigoAlumno'=>$codigoAlumno]);
             if ($client->fault) {
                print_r($result['faultstring']);
            } else {
                $error = $client->getError();
                if ($error) {
                    echo "<h2>Error</h2><pre>" . $error . "</pre>";
                    $errores[]="Codigo Digitado incorrectamentes";
                } else {
                    // Mostrar resultado
                    $response = json_decode($result);
                    if ($response->status=="success") {
                        $datos=json_decode($result);
                        $datosTotales=$datos->datos;
                    }else{
                        /* print_r($result); */
                        $_SESSION['tokenWebservice'] = ""; 
                        $errores[]="Codigo Digitado incorrectamentes";
                    }
                    
                }
            }
        }else{
            // Error, no se genero el token.
        }
        /* La variable es dateToes */
        if($datos->status=="success"){
            /* Aparte de ello se debera de colocar el periodo determinado aqui, pero esto lo definira mediante variable y consulta de la base de datos administrada por parte
            del usuario Administrador */
            if($datosTotales->pagoAutoseguro=="Si" && $datosTotales->periodoPagoAutoseguro==$todoJunto){

                /* Aqui vamos a registrar la parte del alumno a la tabla de alumnos para darle saldo y datos como también llenar el registro del alumno */

                /* Si no esta el alumno generarlo en la base de datos...  */

                $query="SELECT * FROM alumno WHERE codigo_alumno='$codigoAlum'";

                $ejecuta=mysqli_query($db,$query);

                if($ejecuta->num_rows==1){
                    
                    $_SESSION['alumno-pagado']=true;
                    
                    header("Location:./salida-registrar.php?fichador=$fichador&dniMed=$dniMed&fecha=$fecha&codigoAlum=$codigoAlum");

                }else{
                    /* Creación del aporte, veremos la forma de crear el aporte y luego darle el ultimo  */
                    $query="SELECT MAX(id_aporte) AS max_id_aporte FROM aporte;";
                    $ejecuta=mysqli_query($db,$query);
                    $valor=mysqli_fetch_assoc($ejecuta);
                    $ultimoId=$valor['max_id_aporte']+1;
                    
                    
                    /* Almacenando valores */

                    $aporte=8*55;
                    /* Aqui creamos el aporte primero y el saldo inicial correspondido, luego de crear todo eso pasamos a crear la pparte deñ los datos del alumno */
                    $creando="INSERT INTO `aporte`(`id_aporte`, `multiplicador_saldo`, `autoseguro`, `saldo`, `aporte`) VALUES ('$ultimoId',8,55,'$aporte','$aporte')";

                    $ejecutando=mysqli_query($db,$creando);
                    $codigo=$datosTotales->codigo;
                    $nombre_alumno=$datosTotales->apellidosNombres;
                    $facultad=$datosTotales->facultad;
                    $sexo=NULL;
                    $fechaDeDB=null;

                    $creandoAlumno="INSERT INTO `alumno`(`codigo_alumno`, `nombre_alumno`, `facultad`, `sexo`, `fecha_nacimiento`, `id_aporte`) VALUES 
                    ('$codigo','$nombre_alumno','$facultad','$sexo','$fechaDeDB','$ultimoId')";

                    $ejecutarAlumno=mysqli_query($db,$creandoAlumno);
                    /* Redireccionando Nuevo */
                    header("Location:./salida-registrar.php?fichador=$fichador&dniMed=$dniMed&fecha=$fecha&codigoAlum=$codigoAlum");
                }
            }else{
                $_SESSION['alumno-pagado']=false;
                $errores[]="No pago el autoseguro en el ultimo periodo";
            }
        }
    }
    /* El valor del Header */
    /* ;
     */
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title><?php echo $titulo ?></title>
    <link rel="stylesheet" href="../styles/recetario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/c84c24615a.js" crossorigin="anonymous"></script>

</head>
<body>
<div class="container">
    <div id="alertas-container">
        <?php 
            foreach($errores as $errores):?>
            <div class="alerta error"> <?php  echo $errores ;?></div>
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

    <div class="portada-providencial">
        <h1>Salida por Receta</h1>
        <div class="search-container">
            <div class="search">
                <div class="left">
                    <span class="rounded-top"><label for="search-type" class="search-text">Receta</label></span>
                    <div class="line-below"></div>
                </div>
                <div class="molder">
                    <form class="hijo-formulario" method="POST">
                    <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                        <div class="first-molder">
                        <div class="molder-left">
                                <label for="medico-input" class="molder-text">Médico:</label>
                                <!--<input type="text" id="medico-input" name="search-input"> -->
                                <select id="medico-input" name="dni-medico">
                                        <?php if(empty($vendedorId)) : ?>
                                            <option value="" selected>-- Seleccione --</option>
                                        <?php else : ?>
                                            <option value="">-- Seleccione --</option>
                                        <?php endif; ?>
                                        <?php while($vendedor =  mysqli_fetch_assoc($ejecuta_consulta)) : ?>
                                            <option <?php echo $vendedorId === $vendedor['dni_medico'] ? 'selected' : ''; ?> value="<?php echo $vendedor['dni_medico']; ?>">
                                                <?php echo $vendedor['nombre'] . "-" . $vendedor['especialidad']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                </select>
                            </div>
                            
                    
                            <div class="molder-left">
                                <label for="fecha-input" class="molder-text">Fecha:</label>
                                <input type="date" id="fecha-modificar" name="fecha" readonly>
                                <div id="calendar" class="calendar hidden">
                                    <div class="calendar-header">
                                        <button id="prev-month">&lt;</button>
                                        <span id="month-year"></span>
                                        <button id="next-month">&gt;</button>
                                    </div>
                                    <div class="calendar-body">
                                        <div class="calendar-grid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="first-molder">
                        <div class="molder-right">
                                <label for="fichador-input" class="molder-text">Número de Receta:</label>
                                <input type="number" id="fichador-input" name="fichador">
                            </div>
                            
                            <div class="molder-right">
                                <label for="codigo-uni-input" class="molder-text">Código UNI:</label>
                                <input type="text" name="codigo-alumno" id="CodigoAlumno">
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <div class="contenedor-final">
        <div class="salir-receta">
            <a href="<?php if($rol==1){
                    echo('./new-admin.php');
                }else{
                    echo('./new-inicio.php');
                } ?>"><i class='bx bxs-left-arrow'></i>Volver al inicio</a>
        </div>
        <div class="submit-container">
        <button id="envio-form" class="envio-form"><i class='bx bxs-right-arrow'></i>Siguiente</button>
        </div>
    </div>      


</div>

<script nonce="<?php echo $nonce_value; ?>" src="../js/calendario.js"></script>
<script nonce="<?php echo $nonce_value; ?>" src="../js/fichador.js"></script>
<script nonce="<?php echo $nonce_value; ?>" src="../js/upperCase.js"></script>
<script nonce="<?php echo $nonce_value; ?>" src="../js/alerta.js"></script>
<script nonce="<?php echo $nonce_value; ?>" src="../js/recetarioRecetario.js"></script>

</body>
</html>
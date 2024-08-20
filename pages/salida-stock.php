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
    if(!$auth){
        header('Location:../login.php');
    }
    
    if($_SERVER['REQUEST_METHOD']==='POST'){
        var_dump($_POST);
    }
    
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title>Document</title>
    <link rel="stylesheet" href="../styles/salida-stock.css">
</head>
<body>
    <h1 class="title-sdm">SALIDA DE MEDICAMENTOS</h1>
    
    <div class="form-farma">
        <form class="formulario-sm" method="post" id="miFormulario" action="./salida-registrar.php">
        <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
            <div>
                <label for="">N° de Receta:</label>
                <input type="text" placeholder="Introduzca numero de Receta" name="receta">
            </div>
            <div>
                <label for="">Fecha: </label>
                <input type="date" placeholder="Introduzca la fecha" name="fecha">
            </div>
            <div>
                <label for="">Medico: </label>
                <input type="text" placeholder="Introduzca nombre de Medico" name="medico">
            </div>
            <div>
                <label for="">Codigo Alumno UNI: </label>
                <input type="text" placeholder="Introduzca nombre de Digitador" name="codigo">
            </div>
        </form>
    </div>
    <div class="botones">
        <a href="./inicio.php" class="volver">Volver</a>
        <a href="" id="boton" class="avanzar">Avanzar</a>
    </div>

    <script nonce="<?php echo $nonce_value; ?>" src="../js/script.js"></script>
</body>
</html>
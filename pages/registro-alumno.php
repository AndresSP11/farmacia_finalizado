<?php


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
}
header("X-Frame-Options: DENY");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' ka-f.fontawesome.com; form-action 'self'; font-src 'self' cdnjs.cloudflare.com unicons.iconscout.com stackpath.bootstrapcdn.com unpkg.com cdn.jsdelivr.net fonts.gstatic.com; script-src 'self' 'nonce-<?php echo $nonce_value; ?>' cdn.jsdelivr.net code.jquery.com stackpath.bootstrapcdn.com cdnjs.cloudflare.com; style-src 'self' 'nonce-<?php echo $nonce_value; ?>' stackpath.bootstrapcdn.com cdnjs.cloudflare.com unicons.iconscout.com unpkg.com fonts.googleapis.com cdn.jsdelivr.net; img-src 'self' cdn-icons-png.flaticon.com">
    

    <title>Document</title>
    <link rel="stylesheet" href="../styles/registro-alumno.css">
</head>
<body>
    <div class="contenido-padre">
        <div class="container">
            <h1>Registro Alumno</h1>
        </div>
        <div class="form-padre">
            <form method="post" class="formulario-sm">
                <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                <div>
                    <label for="">
                        Nombre:
                    </label>
                    <input name="nombre" type="text" placeholder="Introduzca el nombre">
                </div>
                <div>
                    <label for="">
                        Código:
                    </label>
                    <input name="codigo" type="text" placeholder="Introduzca el Codigo del alumno">
                </div>
                <div>
                    <label for="">
                        Facultad:
                    </label>
                    <select name="facultad" id="">
                        <option value="">-------------Seleccione--------------</option>
                        <option value="">Facultad de Ingenieria Industrial y de Sistemas</option>
                        <option value="">Facultad de Ingenieria Petroleo</option>
                        <option value="">Facultad de Ingenieria Economia y Estadistica</option>
                    </select>
                </div>
                <div>
                    <label for="">
                        Sexo:
                    </label>
                    <select name="sexo" id="">
                        <option value="">-------------Seleccione el sexo--------------</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div>
                    <label for="">
                        Fecha de Nacimiento:
                    </label>
                    <input type="date" name="fecha-nacimiento">
                </div>
            </form>
        </div>
        <div class="botones">
            <a href="./salida-stock.php" class="volver">Volver</a>
            <a href="" class="registrar">Registrar Alumno</a>
        </div>
    </div>
    <script nonce="<?php echo $nonce_value; ?>" src="../js/registro-alumno.js"></script>
</body>
</html>
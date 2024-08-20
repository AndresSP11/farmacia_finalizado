<?php
include_once './config/database.php';
header("X-Frame-Options: DENY");
$db=conectarDB();
session_start();


function generate_nonce() {
    if (!isset($_SESSION['nonce'])) {
        $_SESSION['nonce'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['nonce'];
  }
  $nonce_value = generate_nonce();


function generate_csrf() {
    if (!isset($_SESSION['CSRF'])) {
        $_SESSION['CSRF'] = base64_encode(random_bytes(16));
    }
    return $_SESSION['CSRF'];
  }
  $CSRF = generate_csrf();



$alertas=[];
if($_SERVER['REQUEST_METHOD']=='POST'){
    $login=mysqli_real_escape_string($db,$_POST['login']);
    $password=mysqli_real_escape_string($db,$_POST['password']);

    if(!$login){
        $alertas[]="Es necesario Introducir un Logín";
    }
    if(!$password){
        $alertas[]="La contraseña no puede ir vacia";
    }
    if(empty($alertas)){
        
        $query="SELECT * FROM usuario WHERE usuario='$login'";
        $ejecuta=mysqli_query($db,$query);
    
        /* var_dump($ejecuta); */

        if($ejecuta->num_rows>0){
            $resultado=mysqli_fetch_assoc($ejecuta);
            $auth=password_verify($password,$resultado['contraseña']); 
            if($auth){
                session_start();
                $_SESSION['usuario']=$resultado['nombre'];
                $_SESSION['dni']=$resultado['dni_usuario'];
                $_SESSION['rol']=$resultado['rol'];
                $_SESSION['auth']=true;
                if($resultado['rol']==0){
                    header('Location: ../pages/new-inicio.php');
                }else{
                    header('Location: ../pages/new-admin.php');
                }
                
            }else{
                $alertas[]="Contraseña incorrecta";
            }

        }else{
            $alertas[]="No se ha encontrado al usuario";
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
    <link rel="stylesheet" href="./styles/login.css" nonce="<?php echo $nonce_value; ?>">
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
            </div>
            <div class="portada-providencial">
                <h1>Iniciar Sesión</h1>
                <div class="search-container-receta">

                    <form class="search" method="POST">
                        <input type="hidden" name="CSRF" value="<?php echo $CSRF; ?>">
                        <div class="left-buscar">
                            <div class="input-buscar">
                            <label for="codigo-orden" class="search-text">Introduzca el usuario:</label>
                            <input type="text" id="login" name="login">
                            </div>
                            <div class="input-buscar">
                            <label for="fecha-llegada" class="search-text">Introduzca Contraseña:</label>
                            <input type="password" id="password" name="password"> 
                            </div>
                            <div class="contenedor-final">
                                <div class="submit-container">
                                <button type="submit" class="submit-button">Ingresar<i class='bx bxs-right-arrow'></i></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

        <script src="js/alerta.js" nonce="<?php echo $nonce_value; ?>"></script>


        
</body>
</html>
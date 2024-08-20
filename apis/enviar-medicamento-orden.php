<?php

include_once('../config/database.php');
header("X-Frame-Options: DENY");
$db=conectarDB();

if($_SERVER['REQUEST_METHOD']=='POST'){
    
    $codigo=mysqli_escape_string($db,$_POST['codigo']);

    $just_code=explode('-',$codigo);

    $parametro=$just_code[0];

    $query="SELECT * FROM petitorio WHERE codigo_siga='$parametro'";

    $ejecutar=mysqli_query($db,$query);

    $respuesta=mysqli_fetch_assoc($ejecutar);
    if($ejecutar->num_rows!==0){
        echo json_encode(true);
    }else{
        echo json_encode(false);
    }

}

?>
<?php 

include_once '../config/database.php';
header("X-Frame-Options: DENY");
$db=conectarDB();


if($_SERVER['REQUEST_METHOD']=='POST'){
    /* Insertaremos el query para darle el valor correspondiente */
    $dato=mysqli_real_escape_string($db,$_POST['sol']);

    $variable=mysqli_escape_string($db,$_POST['sol']);
   
    $query="SELECT * FROM `petitorio` WHERE codigo_siga LIKE '$dato%'";
    
    $ejecuta=mysqli_query($db,$query);

    $html="";

    while($ventas=mysqli_fetch_assoc($ejecuta)){
        /* Digamos que el html al inicio esta en cero o es una variable que no existe... El metodo POST lo que hace es mandar siempre el HTML en cero
        la consulta que te devolvera al fina les el HTML pero ya concatenado en los valores correspondientes que son varios divs. */
        $variable1=$ventas['codigo_siga'];
        $variable2=$ventas['denomin_comun_internac_o_principio_activo'];
        $variable3=$ventas['concentracion'];
        $variable4=$ventas['forma_farmaceutica'];
        $html.="<div class='valor'><p class='valorsito' > $variable1-$variable2-$variable3-$variable4</p></div>";
    }
    echo json_encode($html);
}

?>
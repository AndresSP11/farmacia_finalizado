<?php 

include_once '../config/database.php';
header("X-Frame-Options: DENY");
$db=conectarDB();


if($_SERVER['REQUEST_METHOD']=='POST'){
    /* Insertaremos el query para darle el valor correspondiente */
    $dato=mysqli_real_escape_string($db,$_POST['sol']);

    $variable=mysqli_escape_string($db,$_POST['sol']);
   
    $query="SELECT *
    FROM petitorio
    INNER JOIN medicamento ON petitorio.codigo_siga = medicamento.codigo_siga
    WHERE petitorio.denomin_comun_internac_o_principio_activo LIKE '$dato%' ORDER BY fecha_vencimiento;";
    
    $ejecuta=mysqli_query($db,$query);

    $html="";

    while($ventas=mysqli_fetch_assoc($ejecuta)){
        /* Digamos que el html al inicio esta en cero o es una variable que no existe... El metodo POST lo que
        hace es mandar siempre el HTML en cero la consulta que te devolvera al final es el HTML pero ya concatenado
        en los valores correspondientes que son varios divs. */
        if($ventas['cantidad']!=0){
            $variable1=$ventas['cantidad'];
            $variable2=$ventas['denomin_comun_internac_o_principio_activo'];
            $variable3=$ventas['concentracion'];
            $variable4=$ventas['forma_farmaceutica'];
            $variable5=$ventas['id_medicamento'];
            $html.="<div class='valor' id='$variable5'><p class='valorsito'> $variable2-$variable4-$variable5-
                    <span class='cantidad-salida'>$variable1</span></p></div>";
        }
        
    }
    echo json_encode($html);
}
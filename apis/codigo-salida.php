<?php

include_once '../config/database.php';
header("X-Frame-Options: DENY");
$db=conectarDB();


if($_SERVER['REQUEST_METHOD']=='POST'){
    $cantidad=mysqli_real_escape_string($db,$_POST['cantidad']);
    $codigo=mysqli_real_escape_string($db,$_POST['codigo']);
    /* Aqui se tieen que hacer INNERJOIN o Otra consulta simple para obtener el nombre Pero lo recomendable es hacer un innerJoin al parecer  */
    $query="SELECT * from medicamento where id_medicamento='$codigo'";
    $ejecuta=mysqli_query($db,$query);

    $query2="SELECT * FROM petitorio INNER JOIN medicamento ON 
    petitorio.codigo_siga = medicamento.codigo_siga 
    WHERE medicamento.id_medicamento='$codigo'";
    
    $ejecutandoQuery2=mysqli_query($db,$query2);
    if($ejecuta->num_rows!==0){
        $resultado=mysqli_fetch_assoc($ejecuta);
        $resultadoQuery=mysqli_fetch_assoc($ejecutandoQuery2);
        if($resultado['cantidad']>=$cantidad){
            $arreglo=array(
                'probandoCantidad'=>true,
                'codigoMedicamento'=>$resultado['id_medicamento'],
                'precioUnitario'=>$resultado['precio_unitaario'],
                'codigoSiga'=>$resultado['codigo_siga'],
                'nombreMed'=>$resultadoQuery['denomin_comun_internac_o_principio_activo']
            );            
        }else{
            $arreglo=array(
                'condicional'=>false,
                'mensaje'=>"No se puede introducir, no hay mucho almacenamiento",
                'cantidadMedicamentos'=>$resultado['cantidad']
            );    
        }
    }else{
        $arreglo=array(
            'condicional'=>false,
            'mensaje'=>"No existe el codigo que introdujiste"
        );
    }

    echo json_encode($arreglo);
}


?>
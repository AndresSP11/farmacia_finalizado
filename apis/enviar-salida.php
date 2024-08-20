<?php

include_once('../config/database.php');
header("X-Frame-Options: DENY");
$db=conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    session_start();
    
    /* Enviar DNI del tecnico de farmacia */
    $dni=$_SESSION['dni'];

    $medicinas = json_decode($_POST['salidaMedicamentos'], true);

    $todosTienenValor = true; 
    $noPasaElValor=true;
    $contador=0;
    foreach ($medicinas as $medicina) {
        $variableID=$medicina['codigoMedi'];
        $query="SELECT * FROM medicamento WHERE id_medicamento='$variableID'";
        $ejecuta=mysqli_query($db,$query);
        $resultado=mysqli_fetch_assoc($ejecuta);
        if($resultado['cantidad']<$medicina['cantidad']){
            $noPasaElValor=false;
        }
        // Verificar si el elemento tiene un valor (puedes ajustar la condición según tus necesidades)
        if (empty($medicina['cantidad']) || empty($medicina['codigo']) || empty($medicina['codigoMedi']) || 
        empty($medicina['nombre']) || empty($medicina['nombre']) || 
        empty($medicina['precioUni']) || empty($medicina['total'])) {
            $todosTienenValor = false;
            break; // Salir del bucle si al menos un elemento no tiene valor
        }else{
            $contador=$contador+$medicina['cantidad']*$medicina['precioUni'];
        }
    }

    if ($todosTienenValor==true && $noPasaElValor==true) {

        /* Almacenamiento de orden de compra primero para introducir la parte  */
        
        /* Una vez validad la parte que enviamos correspondientemente */

        $fichador = mysqli_escape_string($db,$_POST['fichador']);

        $dniMed = mysqli_escape_string($db,$_POST['dniMed']);

        $fecha = mysqli_escape_string($db,$_POST['fecha']);

        $codigoAlum=mysqli_escape_string($db,$_POST['codigoAlum']);

        /* Validamos que no exista nuevamente un vfichador  */
        $query="SELECT * from receta WHERE fichador_receta='$fichador'";

        $resultado=mysqli_query($db,$query);
        
        
        if($resultado->num_rows!==0){
            
        }else{

            /* Insertamos la Parte del fichador dcorrespondiente.. Luego ahora validaremos la parte de cada medicamento introducito
            mediante FK */
            /* AQUI VAMOS AGREGAR EL PERIODO ACADEMICO */
            $query="SELECT * FROM periodo_academico";
            
            $respuesta=mysqli_query($db,$query);

            $obtenerDato=mysqli_fetch_assoc($respuesta);

            $cicloAcademico=$obtenerDato['periodo'];

            //Aca debemos de insertar los valores correspondientes ahora si mediante nuevamente el forEach  pero primero inserTamos la nueva Orden
            $query="INSERT INTO `receta`(`fichador_receta`, `fecha`, `monto_total`, `dni_usuario`, `codigo_alumno`,`dni_medico`,`periodo_academico`) VALUES ('$fichador','$fecha','$contador','$dni','$codigoAlum','$dniMed','$cicloAcademico')";

            $resultado=mysqli_query($db,$query);
            //Okey una vez introducido ahora insertaremos a los medicamentos cada uno
            /* $mostrar=mysqli_fetch_assoc($resultado);
            $ordenCompra=$mostrar['id_orden']; */
            /* AQUI VAMOS A INTROUCIR UNA DOBLE INTRO DEBIDO A  */
            /* INSERT INTO `petitorio_orden`(`cantidad`, `precio_unitario`, `unidad_medida`, `fecha_vencimiento`, `lote`, `precio_total`, `id_laboratorio`, `id_orden`, `codigo_siga`)
                 VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]') */
            
            foreach ($medicinas as $medicina){

                $arreglo=[];
                $arreglo[]=$medicina;
                $cantidad=$medicina['cantidad'];
                $codigo=$medicina['codigoMedi'];
                $precio=$medicina['precioUni'];
                /* Precio total */
                $precioTotal=$cantidad*$precio;
                $query="INSERT INTO `medicamento_receta`(`cantidad`, `precio_total`, `fichador_receta`, `id_medicamento`) VALUES ('$cantidad','$precioTotal','$fichador','$codigo')";
                /* Ejecutando el query */
                $resultado=mysqli_query($db,$query);
                
                /* Obteniendo el nombre primero en base al codigo_siga */

                /* Terminando la aprte de select */


                $query_update="SELECT * FROM medicamento WHERE id_medicamento='$codigo'";

                $ejecuta=mysqli_query($db,$query_update);

                $resultado=mysqli_fetch_assoc($ejecuta);

                $cantidadDelInventario=$resultado['cantidad'];

                $cantidadReal=$cantidadDelInventario-$cantidad;

                $query_update="UPDATE `medicamento` SET 
                `cantidad`='$cantidadReal'  WHERE id_medicamento='$codigo'";

                $ejecuta=mysqli_query($db,$query_update);
            };

            /* Parte en donde se va quitar el saldo o disminuir por gastos economicos 

            
            */

            $query="SELECT * FROM alumno WHERE codigo_alumno='$codigoAlum'";

            $ejecuta=mysqli_query($db,$query);

            $mostrarFK=mysqli_fetch_assoc($ejecuta);

            $obteniendo=$mostrarFK['id_aporte'];

            /* Haremos la resta necesaria */

            $consultarSaldo="SELECT * FROM aporte WHERE id_aporte='$obteniendo'";

            $ejecuta=mysqli_query($db,$consultarSaldo);

            $resultado=mysqli_fetch_assoc($ejecuta);

            $saldo=$resultado['saldo'];

            $saldoCambio=$saldo-$contador;

            $query="UPDATE `aporte` SET `saldo`='$saldoCambio' WHERE id_aporte='$obteniendo'";

            $ejecutarCambio=mysqli_query($db,$query);

            $arreglo = array(
                'verdadero' => true,
                'falso' => false
            );
            echo json_encode($arreglo);
        }
    } else {
        // Al menos un elemento no tiene valor, puedes enviar false o realizar otras acciones
        echo json_encode(false);
    }

}
?>
<?php

include_once('../config/database.php');
header("X-Frame-Options: DENY");
$db=conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    session_start();
    
    /* DNI de */
    $dni=$_SESSION['dni'];

    $medicinas = json_decode($_POST['localidad'], true);

    $todosTienenValor = true; 
    $contador=0;
    foreach ($medicinas as $medicina) {
        // Verificar si el elemento tiene un valor (puedes ajustar la condición según tus necesidades)
        if (empty($medicina['cantidad']) || empty($medicina['codigo']) || empty($medicina['fecha']) || empty($medicina['laboratorio']) || empty($medicina['numberLote']) || empty($medicina['precio'])) {
            $todosTienenValor = false;
            break; // Salir del bucle si al menos un elemento no tiene valor
        }else{
            $contador=$contador+$medicina['cantidad']*$medicina['precio'];
        }
    }

    if ($todosTienenValor) {

        /* Almacenamiento de orden de compra primero para introducir la parte  */
        /* Valores enviador por la parte del frontend :D */
        $codigoOrden = mysqli_escape_string($db,$_POST['codigoOrden']);
        $fechaLlegada = mysqli_escape_string($db,$_POST['fechaLlegada']);
        $proveedor = mysqli_escape_string($db,$_POST['proveedor']);
        $dinero=mysqli_escape_string($db,$_POST['dinero']);

        $query="SELECT * from orden_de_compra WHERE id_orden='$codigoOrden'";
        $resultado=mysqli_query($db,$query);
        
        if($resultado->num_rows!==0){
            
        }else{
            //Aca debemos de insertar los valores correspondientes ahora si mediante nuevamente el forEach  pero primero inserTamos la nueva Orden
            $query="INSERT INTO `orden_de_compra`(`id_orden`, `fecha`, `proveedor`, `monto_total`, `dni_usuario`) VALUES ('$codigoOrden','$fechaLlegada','$proveedor','$contador','$dni')";

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
                $codigo=$medicina['codigo'];
                $codigoReal=explode("-",$codigo);
                $codigo=$codigoReal[0];
                $fecha=$medicina['fecha'];
                $laboratorio=$medicina['laboratorio'];
                $numeroLote=$medicina['numberLote'];
                /* Precio del medicamento */
                $precio=$medicina['precio']; 
                $precioTotal=$cantidad*$precio;
                $query="INSERT INTO `petitorio_orden`(`cantidad`, `precio_unitario`, `unidad_medida`, `fecha_vencimiento`, `lote`, `precio_total`, `id_laboratorio`, `id_orden`, `codigo_siga`) VALUES ('$cantidad','$precio','UNIDAD','$fecha','$numeroLote','$precioTotal','1','$codigoOrden','$codigo')";
                /* Ejecutando el query */
                $resultado=mysqli_query($db,$query);
                
                /* Obteniendo el nombre primero en base al codigo_siga */

                $query_busqueda="SELECT * FROM petitorio WHERE codigo_siga='$codigo'";

                $ejecuta=mysqli_query($db,$query_busqueda);

                $respuesta=mysqli_fetch_assoc($ejecuta);

                /* Creando la variable  */
                
                $codigo_siga_far=$respuesta['codigo_siga'];
                $ultimoDos=substr($codigo_siga_far,-2);
                $codigo_en_cuatro=substr($codigo_siga_far,0,4);
                $nombre=$respuesta['denomin_comun_internac_o_principio_activo'];

                function quitarTildes($cadena) {
                    $tildes = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú');
                    $sinTildes = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U');
                    return strtr($cadena, array_combine($tildes, $sinTildes));
                }
                
                $palabra_con_tildes = "áéíóúÁÉÍÓÚ";
                $palabra_sin_tildes = quitarTildes($nombre);

                $nombre=$palabra_sin_tildes;

                $nombre_en_cuatro=substr($nombre,0,4);
                
                $particion_precio=explode(".",$precio);

                $parte_entera=$particion_precio[0];

                $parte_decimal=(isset($particion_precio[1])) ? $particion_precio[1] : "";

                $parte_decimal=str_pad($parte_decimal,2,"0",STR_PAD_RIGHT);

                $numero_real=$parte_entera.$parte_decimal;

                $nombre_insertar_db=$codigo_en_cuatro.$ultimoDos.$nombre_en_cuatro.$numero_real;

                $query="SELECT * FROM medicamento WHERE id_medicamento='$nombre_insertar_db'";

                $ejecuta=mysqli_query($db,$query);

                if($ejecuta->num_rows){
                    /* okey aqui si existe  */

                    /* La logica, va consistir de la siguiente forma */

                    $resultadoSiHay=mysqli_fetch_assoc($ejecuta);

                    if($fecha==$resultadoSiHay['fecha_vencimiento']){

                        $cantidadSiHay=$resultadoSiHay['cantidad'];

                        $cantidadTotal=$cantidadSiHay+$cantidad;

                        $query="UPDATE `medicamento` SET `cantidad`='$cantidadTotal' WHERE id_medicamento='$nombre_insertar_db'";

                        $ejecutando=mysqli_query($db,$query);
                        
                        
                        /* 14.10 2024-05-24 351000025672 */

                    }else{
                        
                        $query_filas="SELECT * FROM medicamento WHERE precio_unitaario='$precio' AND codigo_siga=$codigo";

                        /* En esta oportunidad ahora ejecutamso el query */

                        $ejecuta_filas=mysqli_query($db,$query_filas);

                        $numero_filas=mysqli_num_rows($ejecuta_filas);

                        $nombre_id_med=$nombre_insertar_db.'-'.$numero_filas;

                        /* Aca se introduce los id del medicamento pero con esto ya modificado haciendo la consulta respectiva */
                        /* numeroLote */
                        $query="INSERT INTO `medicamento`(`id_medicamento`, `cantidad`, `precio_unitaario`, `fecha_vencimiento`,`codigo_siga`,`numero_lote`) VALUES ('$nombre_id_med','$cantidad','$precio','$fecha','$codigo','$numeroLote')";
                    
                        $ejecuta=mysqli_query($db,$query);

                    }

                    
                    

                }else{
                    /* Okey aqui no existe entonces debemos de insertar */
                    /* INSERT INTO `medicamento`(`id_medicamento`, `cantidad`, 
                    `estado`, `precio_unitaario`, `fecha_vencimiento`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]') */
                    $query="INSERT INTO `medicamento`(`id_medicamento`, `cantidad`, `precio_unitaario`, `fecha_vencimiento`,`codigo_siga`,`numero_lote`) VALUES ('$nombre_insertar_db','$cantidad','$precio','$fecha','$codigo','$numeroLote')";
                    
                    $ejecuta=mysqli_query($db,$query);
                }
            };
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
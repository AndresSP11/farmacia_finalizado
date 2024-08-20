<?php
    // print_r("---");exit;
    include('nusoap.php');
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
                    }
                }
            }
        }

        /* AQUI LOS VALORES CON RESULTADOS  */

        if (isset($_SESSION["tokenWebservice"]) && $_SESSION["tokenWebservice"]!="") {
            // $codigoAlumno = "19850373K";
            $codigoAlumno = "20200284G";
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
                } else {
                    // Mostrar resultado
                    $response = json_decode($result);
                    if ($response->status=="success") {
                        echo("Motrando elemento");
                        echo("<br>");
                        echo($result);
                        exit;
                    }else{
                        print_r($result);
                        $_SESSION['tokenWebservice'] = ""; 
                    }
                    exit;
                }
            }
        }else{
            // Error, no se genero el token.
        }
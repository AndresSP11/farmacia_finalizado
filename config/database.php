<?php 

function conectarDB() : mysqli {
    $db = mysqli_connect('localhost', 'root', '', 'base_farmacia');

    if(!$db) {
        echo "Error no se pudo conectar";
        exit;
    } 
    return $db;
}   


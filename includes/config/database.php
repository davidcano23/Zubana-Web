<?php

function conectarDB() : mysqli {
    $db = new mysqli(
        'localhost',          // host — en Hostinger casi siempre es 'localhost'
        'TU_USUARIO_DB',      // usuario de la base de datos (panel Hostinger)
        'TU_PASSWORD_DB',     // contraseña
        'TU_NOMBRE_DB'        // nombre de la base de datos
    );

    if(!$db) {
        echo 'Error 404 conexión Database';
        exit;
    }

    return $db; // ✅ Esto se ejecuta si la conexión fue exitosa
}

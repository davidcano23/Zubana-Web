<?php

function conectarDB() : mysqli {
    $db = new mysqli(
        'localhost',          // host — en Hostinger casi siempre es 'localhost'
        'root',      // usuario de la base de datos (panel Hostinger)
        'root',     // contraseña
        'zubanabienraiz'        // nombre de la base de datos
    );

    if(!$db) {
        echo 'Error 404 conexión Database';
        exit;
    }

    return $db; // ✅ Esto se ejecuta si la conexión fue exitosa
}

<?php

function conectarDB() : mysqli {  
    $db = new mysqli('localhost', 'root', 'root', 'zubanabienraiz');

    if(!$db) {
        echo 'Error 404 conexión Database';
        exit;
    }

    return $db; // ✅ Esto se ejecuta si la conexión fue exitosa
}

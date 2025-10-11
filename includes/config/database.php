<?php

function conectarDB() : mysqli {  
<<<<<<< HEAD
    $db = new mysqli('localhost', 'root', 'root', 'zubanabienraiz');
=======
    $db = new mysqli('localhost', 'u516090615_Hostinger', '3103817479DC_m', 'u516090615_zubana');
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189

    if(!$db) {
        echo 'Error 404 conexión Database';
        exit;
    }

    return $db; // ✅ Esto se ejecuta si la conexión fue exitosa
}

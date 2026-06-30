<?php

require 'funciones.php';
require 'config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';



//Conectarnos a la base de datos
    $db = conectarDB();


use Model\ActiveRecord;

ActiveRecord::setDB($db);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['tenant_id'])) {
    // Zona privada (panel): el tenant sale de la sesión, fijada en el login
    ActiveRecord::setTenant((int) $_SESSION['tenant_id']);
} else {
    // Zona pública: temporalmente tenant 1 (en la Fase 5 se resolverá por subdominio)
    ActiveRecord::setTenant(1);
}


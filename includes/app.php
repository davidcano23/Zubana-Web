<?php

require 'funciones.php';
require 'config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';



//Conectarnos a la base de datos
    $db = conectarDB();


use Model\ActiveRecord;

ActiveRecord::setDB($db);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION['tenant_id'])) {
    // ZONA PRIVADA: el usuario ya inició sesión, su tenant manda
    ActiveRecord::setTenant((int) $_SESSION['tenant_id']);
} else {
    // ZONA PÚBLICA: resolver por subdominio
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $sub  = explode('.', $host)[0];        // 'zubana' de 'zubana.tudominio.com'

    $tenant = Model\Tenant::porSubdominio($sub);

    if (!$tenant || $tenant->estado === 'suspendido') {
        http_response_code(404);
        exit('Inmobiliaria no encontrada');
    }

    ActiveRecord::setTenant((int) $tenant->id);
}


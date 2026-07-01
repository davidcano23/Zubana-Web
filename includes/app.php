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
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $sub  = explode('.', $host)[0];

    // --- SOLO PARA DESARROLLO EN LOCALHOST ---
    if ($host === 'localhost' || strpos($host, 'localhost:') === 0 || strpos($host, '127.0.0.1') === 0) {
        if (isset($_GET['tenant'])) {
            $_SESSION['dev_tenant'] = preg_replace('/[^a-z0-9-]/', '', strtolower($_GET['tenant']));
        }
        $sub = $_SESSION['dev_tenant'] ?? 'zubana';
    }
    // --- FIN MODO DESARROLLO ---

    $tenant = Model\Tenant::porSubdominio($sub);
    if (!$tenant || $tenant->estado === 'suspendido') {
        http_response_code(404);
        exit('Inmobiliaria no encontrada');
    }
    ActiveRecord::setTenant((int) $tenant->id);
}


<?php

require 'funciones.php';
require 'config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';



//Conectarnos a la base de datos
    $db = conectarDB();


use Model\ActiveRecord;

ActiveRecord::setDB($db);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// --- BYPASS SUPERADMIN (Fase 7) ---
// El panel /superadmin/* es NUESTRO, no pertenece a ninguna inmobiliaria:
// no se resuelve tenant. ActiveRecord queda SIN tenant a propósito —
// si una consulta de negocio se ejecutara aquí sin contexto, tid()
// lanza 'Tenant no resuelto' (la protección de la Fase 4 sigue intacta).
$rutaActual = $_SERVER['PATH_INFO'] ?? '/';
if (strpos($rutaActual, '/superadmin') === 0) {
    return;   // corta app.php aquí; index.php sigue con las rutas
}
// --- FIN BYPASS SUPERADMIN ---

$host    = $_SERVER['HTTP_HOST'] ?? '';
$esLocal = $host === 'localhost' || strpos($host, 'localhost:') === 0 || strpos($host, '127.0.0.1') === 0;

// En desarrollo, ?tenant=X SIEMPRE manda (aunque haya sesión iniciada de
// otro tenant): así puedes visitar el sitio público de cualquier
// inmobiliaria para probar sin cerrar sesión.
$devTenantExplicito = $esLocal && isset($_GET['tenant']);
if ($devTenantExplicito) {
    $_SESSION['dev_tenant'] = preg_replace('/[^a-z0-9-]/', '', strtolower($_GET['tenant']));

    // Emular producción: cada subdominio tiene SU propia sesión. Si hay
    // una sesión abierta de OTRO tenant, se cierra aquí. Así nunca se
    // mezclan la sesión de una inmobiliaria con el sitio de otra
    // (botones de admin de Zubana sobre las propiedades de demo, etc.)
    if (isset($_SESSION['tenant_id'])) {
        $tenantSesion = Model\Tenant::porId((int) $_SESSION['tenant_id']);
        if (!$tenantSesion || $tenantSesion->subdominio !== $_SESSION['dev_tenant']) {
            unset($_SESSION['login'], $_SESSION['usuario'], $_SESSION['tenant_id']);
        }
    }
}

if (isset($_SESSION['tenant_id']) && !$devTenantExplicito) {
    // ZONA PRIVADA: el usuario ya inició sesión, su tenant manda...
    // pero SU ESTADO TAMBIÉN se verifica: si lo suspendimos desde el
    // panel, la sesión abierta deja de servirle en ese instante.
    $tenant = Model\Tenant::porId((int) $_SESSION['tenant_id']);

    if (!$tenant || Model\Tenant::normalizarEstado($tenant->estado) === 'suspendido') {
        // Cerrar SOLO la sesión del tenant (sin tocar la de superadmin)
        unset($_SESSION['login'], $_SESSION['usuario'], $_SESSION['tenant_id']);
        http_response_code(404);
        exit('Inmobiliaria no encontrada');
    }
    ActiveRecord::setTenant((int) $tenant->id);
} else {
    $sub = explode('.', $host)[0];

    // --- SOLO PARA DESARROLLO EN LOCALHOST ---
    if ($esLocal) {
        $sub = $_SESSION['dev_tenant'] ?? 'zubana';
    }
    // --- FIN MODO DESARROLLO ---

    $tenant = Model\Tenant::porSubdominio($sub);
    if (!$tenant || Model\Tenant::normalizarEstado($tenant->estado) === 'suspendido') {
        http_response_code(404);
        exit('Inmobiliaria no encontrada');
    }
    ActiveRecord::setTenant((int) $tenant->id);
}


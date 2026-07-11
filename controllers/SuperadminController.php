<?php

namespace Controllers;

use Model\Superadmin;
use Model\Tenant;
use Model\Casa;
use Model\Apartamento;
use Model\Local;
use Model\Lote;
use MVC\Router;

/**
 * Panel privado NUESTRO (Fase 7).
 * Las rutas /superadmin/* están protegidas en Router::comprobarRutas()
 * y NO dependen de ningún tenant (ver bypass en includes/app.php).
 */
class SuperadminController {

    // GET/POST /superadmin/login
    public static function login(Router $router) {
        $errores = [];

        // Si ya hay sesión de superadmin, directo al panel
        if (Superadmin::autenticado()) {
            header('Location: /superadmin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El email no es válido.';
            }
            if ($password === '') {
                $errores[] = 'El password es obligatorio.';
            }

            if (empty($errores)) {
                $superadmin = Superadmin::porEmail($email);

                // Mensaje genérico a propósito: no revelamos si el email existe
                if (!$superadmin || !$superadmin->comprobarPassword($password)) {
                    $errores[] = 'Credenciales incorrectas.';
                } else {
                    $superadmin->iniciarSesion();
                    header('Location: /superadmin');
                    exit;
                }
            }
        }

        $router->renderSuperadmin('superadmin/login', [
            'errores' => $errores,
            'titulo'  => 'Acceso Superadmin',
        ]);
    }

    // GET /superadmin/logout
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        Superadmin::cerrarSesion();
        header('Location: /superadmin/login');
        exit;
    }

    /**
     * Arma TODAS las métricas del panel en un solo array.
     * La usan el dashboard (index) y el endpoint JSON (metricas).
     * Solo se llama desde rutas ya protegidas por el guard del Router.
     */
    private static function obtenerMetricas(): array {
        $porEstado = Tenant::contarPorEstado();
        $porTenant = Tenant::resumenPorTenant();

        $porTipo = [
            'casas'        => Casa::contarGlobal(),
            'apartamentos' => Apartamento::contarGlobal(),
            'locales'      => Local::contarGlobal(),
            'lotes'        => Lote::contarGlobal(),
        ];

        // Totales derivados (sin más consultas)
        $totalPropiedades = array_sum($porTipo);
        $totalUsuarios    = array_sum(array_map(fn($t) => (int) $t->usuarios, $porTenant));
        $totalTenants     = array_sum($porEstado);

        // Registros recientes: los 5 últimos (resumenPorTenant trae created_at)
        $recientes = $porTenant;
        usort($recientes, fn($a, $b) => strcmp($b->created_at, $a->created_at));
        $recientes = array_slice($recientes, 0, 5);

        return [
            'porEstado'        => $porEstado,
            'porTipo'          => $porTipo,
            'porTenant'        => $porTenant,
            'recientes'        => $recientes,
            'crecimiento'      => Tenant::crecimientoPorMes(),
            'totalPropiedades' => $totalPropiedades,
            'totalUsuarios'    => $totalUsuarios,
            'totalTenants'     => $totalTenants,
        ];
    }

    // GET /superadmin — dashboard con métricas reales (Tarea 3)
    public static function index(Router $router) {
        $metricas = self::obtenerMetricas();

        $router->renderSuperadmin('superadmin/index', $metricas + [
            'titulo' => 'Panel Superadmin',
            'nombre' => $_SESSION['superadmin_nombre'] ?? 'Superadmin',
        ]);
    }

    // GET /superadmin/api/metricas — las mismas cifras en JSON.
    // Lo consumirán las gráficas de Chart.js (Tarea 5).
    public static function metricas() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(self::obtenerMetricas());
        exit;
    }

    // ============ Tareas 6 y 7: mapa de propiedades por zona ============

    // GET /superadmin/mapa — página del mapa de Colombia
    public static function mapa(Router $router) {
        $router->renderSuperadmin('superadmin/mapa', [
            'titulo'      => 'Mapa de propiedades',
            'modalidades' => Tenant::modalidadesDisponibles(),
        ]);
    }

    // GET /superadmin/api/mapa?tipo=casa&modalidad=Venta — zonas en JSON.
    // Ambos filtros son opcionales; el modelo los valida/escapa.
    public static function apiMapa() {
        $tipo      = $_GET['tipo'] ?? null;
        $modalidad = $_GET['modalidad'] ?? null;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'zonas' => Tenant::propiedadesPorZona($tipo ?: null, $modalidad ?: null),
        ]);
        exit;
    }

    // ================= Tarea 4: gestión de inmobiliarias =================

    // GET /superadmin/inmobiliarias — listado con búsqueda y acciones
    public static function inmobiliarias(Router $router) {
        $router->renderSuperadmin('superadmin/inmobiliarias', [
            'titulo'        => 'Inmobiliarias',
            'inmobiliarias' => Tenant::resumenPorTenant(),
            'exito'         => $_GET['exito'] ?? null,
        ]);
    }

    // GET/POST /superadmin/inmobiliarias/crear — alta desde el panel.
    // Reutiliza validarAlta() y altaConAdmin(): las MISMAS reglas y la
    // misma transacción que el registro público, pero permite elegir
    // el estado inicial (por defecto 'activa': la creamos nosotros).
    public static function inmobiliariaCrear(Router $router) {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre     = trim($_POST['nombre'] ?? '');
            $subdominio = strtolower(trim($_POST['subdominio'] ?? ''));
            $email      = trim($_POST['email'] ?? '');
            $password   = $_POST['password'] ?? '';
            $estado     = $_POST['estado'] ?? 'activo';

            $errores = Tenant::validarAlta($nombre, $subdominio, $email, $password);

            if (empty($errores)) {
                try {
                    Tenant::altaConAdmin($nombre, $subdominio, $email, $password, $estado);
                    header('Location: /superadmin/inmobiliarias?exito=creada');
                    exit;
                } catch (\Throwable $e) {
                    $errores[] = 'No se pudo crear la inmobiliaria. Intenta de nuevo.';
                }
            }
        }

        $router->renderSuperadmin('superadmin/inmobiliaria-crear', [
            'titulo'     => 'Nueva inmobiliaria',
            'errores'    => $errores,
            'nombre'     => $_POST['nombre'] ?? '',
            'subdominio' => $_POST['subdominio'] ?? '',
            'email'      => $_POST['email'] ?? '',
        ]);
    }

    // POST /superadmin/inmobiliarias/estado — activar / suspender / prueba
    public static function inmobiliariaEstado() {
        $id     = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? '';

        if ($id > 0) {
            try {
                Tenant::cambiarEstado($id, $estado);
            } catch (\Throwable $e) {
                // estado inválido → simplemente no se aplica
            }
        }
        header('Location: /superadmin/inmobiliarias?exito=estado');
        exit;
    }
}

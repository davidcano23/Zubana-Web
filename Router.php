<?php

namespace MVC;

class Router {

    public $rutasGET  = [];
    public $rutasPOST = [];

    public function get($url, $fn): void {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn): void {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $auth = $_SESSION['login'] ?? null;

        $rutas_protegidas = [
            '/tipo-propiedad',
            '/propiedades/crear-casa',
            '/propiedades/actualizar-casa',
            '/propiedades/crear-finca',
            '/propiedades/actualizar-finca',
            '/propiedades/crear-apartamento',
            '/propiedades/actualizar-apartamento',
            '/propiedades/crear-lote',
            '/propiedades/actualizar-lote',
            '/propiedades/crear-local',
            '/propiedades/actualizar-local',
            '/propiedades/eliminar',
            '/crm/whatsapp',
            '/crm',
            '/crm/crear',
            '/crm/cliente',
            '/crm/editar',
            '/crm/actividad',
            '/crm/estado',
            '/crm/eliminar',
            '/configuraciones',
            '/configuraciones/guardar',
            '/configuraciones/reset',
        ];

        // PATH_INFO may be absent on some hosts (e.g. Hostinger); fall back to REQUEST_URI.
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';

        if (!isset($_SERVER['PATH_INFO']) || $urlActual === '/') {
            $requestUri = $_SERVER['REQUEST_URI'];
            $pos = strpos($requestUri, '?');
            $urlActual = $pos !== false ? substr($requestUri, 0, $pos) : $requestUri;
        }

        $metodo = $_SERVER['REQUEST_METHOD'];
        $fn     = $metodo === 'GET'
            ? ($this->rutasGET[$urlActual]  ?? null)
            : ($this->rutasPOST[$urlActual] ?? null);

        if (in_array($urlActual, $rutas_protegidas)) {
            if (!$auth || !isset($_SESSION['tenant_id'])) {
                header('location: /');
                exit;
            }
        }

        // --- Guard del panel superadmin (Fase 7) ---
        // Protege TODO /superadmin/* por prefijo (no lista manual: cualquier
        // ruta nueva del panel queda protegida automáticamente).
        // Única excepción: el propio login.
        if (strpos($urlActual, '/superadmin') === 0 && $urlActual !== '/superadmin/login') {
            if (($_SESSION['superadmin'] ?? false) !== true) {
                header('location: /superadmin/login');
                exit;
            }
        }

        if ($fn) {
            call_user_func($fn, $this);
        } else {
            echo "pagina no encontrada";
        }
    }

    public function render($view, $datos = []): void {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();
        include_once __DIR__ . '/views/layout.php';
    }

    // Igual que render() pero con el layout del panel superadmin.
    // El layout normal (views/layout.php) arma metadatos de propiedades
    // del tenant; el panel privado no tiene nada de eso.
    public function renderSuperadmin($view, $datos = []): void {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();
        include_once __DIR__ . '/views/superadmin/layout.php';
    }
}

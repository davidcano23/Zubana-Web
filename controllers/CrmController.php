<?php

namespace Controllers;

use MVC\Router;
use Model\Cliente;
use Model\CrmActividad;

class CrmController {

    private static function requireAuth(): void {
        if (empty($_SESSION['login'])) {
            header('Location: /');
            exit;
        }
    }

    private static function flash(string $key, string $msg): void {
        $_SESSION[$key] = $msg;
    }

    private static function getFlash(string $key): ?string {
        $msg = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        return $msg;
    }

    // GET /crm
    public static function index(Router $router): void {
        self::requireAuth();

        $q      = trim($_GET['q']      ?? '');
        $estado = trim($_GET['estado'] ?? '');
        $vista  = in_array($_GET['vista'] ?? '', ['pipeline', 'lista']) ? $_GET['vista'] : 'pipeline';

        $clientes      = Cliente::buscar($q, $estado);
        $porEstado     = Cliente::porEstado();
        $conteo        = Cliente::contarPorEstado();
        $totalClientes = array_sum($conteo);
        $etapas        = Cliente::etapas();

        $router->render('crm/index', [
            'clientes'      => $clientes,
            'porEstado'     => $porEstado,
            'conteo'        => $conteo,
            'totalClientes' => $totalClientes,
            'etapas'        => $etapas,
            'q'             => $q,
            'estadoFiltro'  => $estado,
            'vista'         => $vista,
            'exito'         => self::getFlash('crm_exito'),
            'error'         => self::getFlash('crm_error'),
        ]);
    }

    // GET /crm/crear  |  POST /crm/crear
    public static function crear(Router $router): void {
        self::requireAuth();

        $errores = [];
        $cliente = new Cliente();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente->sincronizar([
                'nombre'          => trim($_POST['nombre']         ?? ''),
                'apellido'        => trim($_POST['apellido']       ?? ''),
                'telefono'        => trim($_POST['telefono']       ?? ''),
                'email'           => trim($_POST['email']          ?? ''),
                'ciudad'          => trim($_POST['ciudad']         ?? ''),
                'presupuesto_min' => (int) preg_replace('/\D/', '', $_POST['presupuesto_min'] ?? '0'),
                'presupuesto_max' => (int) preg_replace('/\D/', '', $_POST['presupuesto_max'] ?? '0'),
                'tipo_busqueda'   => $_POST['tipo_busqueda']  ?? 'Compra',
                'tipo_propiedad'  => trim($_POST['tipo_propiedad'] ?? ''),
                'fuente'          => $_POST['fuente']          ?? 'Otro',
                'estado'          => $_POST['estado']          ?? 'nuevo',
                'notas'           => trim($_POST['notas']          ?? ''),
            ]);

            $errores = $cliente->validar();

            if (empty($errores)) {
                if ($cliente->crearCliente()) {
                    self::flash('crm_exito', 'Cliente creado correctamente.');
                    header("Location: /crm/cliente?id={$cliente->id}");
                    exit;
                }
                $errores[] = 'Error al guardar. Intenta de nuevo.';
            }
        }

        $router->render('crm/formulario', [
            'cliente'  => $cliente,
            'errores'  => $errores,
            'esNuevo'  => true,
            'etapas'   => Cliente::etapas(),
            'fuentes'  => Cliente::fuentes(),
            'tiposB'   => Cliente::tiposBusqueda(),
            'tiposP'   => Cliente::tiposPropiedad(),
        ]);
    }

    // GET /crm/cliente?id=X
    public static function detalle(Router $router): void {
        self::requireAuth();

        $id      = (int) ($_GET['id'] ?? 0);
        $cliente = Cliente::find($id);

        if (!$cliente) {
            header('Location: /crm');
            exit;
        }

        $router->render('crm/detalle', [
            'cliente'     => $cliente,
            'actividades' => CrmActividad::getByCliente($id),
            'etapas'      => Cliente::etapas(),
            'tiposAct'    => CrmActividad::tipos(),
            'exito'       => self::getFlash('crm_exito'),
            'error'       => self::getFlash('crm_error'),
        ]);
    }

    // GET /crm/editar?id=X  |  POST /crm/editar
    public static function editar(Router $router): void {
        self::requireAuth();

        $id      = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $cliente = Cliente::find($id);

        if (!$cliente) {
            header('Location: /crm');
            exit;
        }

        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente->sincronizar([
                'nombre'          => trim($_POST['nombre']         ?? ''),
                'apellido'        => trim($_POST['apellido']       ?? ''),
                'telefono'        => trim($_POST['telefono']       ?? ''),
                'email'           => trim($_POST['email']          ?? ''),
                'ciudad'          => trim($_POST['ciudad']         ?? ''),
                'presupuesto_min' => (int) preg_replace('/\D/', '', $_POST['presupuesto_min'] ?? '0'),
                'presupuesto_max' => (int) preg_replace('/\D/', '', $_POST['presupuesto_max'] ?? '0'),
                'tipo_busqueda'   => $_POST['tipo_busqueda']  ?? 'Compra',
                'tipo_propiedad'  => trim($_POST['tipo_propiedad'] ?? ''),
                'fuente'          => $_POST['fuente']          ?? 'Otro',
                'estado'          => $_POST['estado']          ?? 'nuevo',
                'notas'           => trim($_POST['notas']          ?? ''),
            ]);

            $errores = $cliente->validar();

            if (empty($errores)) {
                if ($cliente->actualizarCliente()) {
                    self::flash('crm_exito', 'Cliente actualizado correctamente.');
                    header("Location: /crm/cliente?id={$id}");
                    exit;
                }
                $errores[] = 'Error al actualizar. Intenta de nuevo.';
            }
        }

        $router->render('crm/formulario', [
            'cliente'  => $cliente,
            'errores'  => $errores,
            'esNuevo'  => false,
            'etapas'   => Cliente::etapas(),
            'fuentes'  => Cliente::fuentes(),
            'tiposB'   => Cliente::tiposBusqueda(),
            'tiposP'   => Cliente::tiposPropiedad(),
        ]);
    }

    // POST /crm/actividad  (AJAX → JSON)
    public static function actividad(Router $router): void {
        self::requireAuth();
        header('Content-Type: application/json');

        $clienteId   = (int)   ($_POST['cliente_id']  ?? 0);
        $tipo        = trim(   $_POST['tipo']          ?? 'nota');
        $descripcion = trim(   $_POST['descripcion']   ?? '');

        if (!$clienteId || !$descripcion) {
            echo json_encode(['ok' => false, 'msg' => 'Datos incompletos.']);
            exit;
        }

        $tipos = CrmActividad::tipos();
        $act   = new CrmActividad();
        $act->cliente_id  = $clienteId;
        $act->tipo        = array_key_exists($tipo, $tipos) ? $tipo : 'nota';
        $act->descripcion = $descripcion;

        if ($act->crearActividad()) {
            echo json_encode([
                'ok'          => true,
                'id'          => $act->id,
                'tipo'        => $act->tipo,
                'tipoLabel'   => $tipos[$act->tipo]['label'],
                'tipoIco'     => $tipos[$act->tipo]['ico'],
                'descripcion' => htmlspecialchars($descripcion, ENT_QUOTES),
                'fecha'       => date('d M Y · H:i'),
            ]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Error al guardar la actividad.']);
        }
        exit;
    }

    // POST /crm/estado  (AJAX → JSON)
    public static function estado(Router $router): void {
        self::requireAuth();
        header('Content-Type: application/json');

        $id     = (int) ($_POST['id']     ?? 0);
        $estado = trim( $_POST['estado']  ?? '');

        if (Cliente::cambiarEstado($id, $estado)) {
            $etapas = Cliente::etapas();
            echo json_encode([
                'ok'    => true,
                'label' => $etapas[$estado]['label'],
                'color' => $etapas[$estado]['color'],
            ]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Estado no válido.']);
        }
        exit;
    }

    // POST /crm/eliminar
    public static function eliminar(Router $router): void {
        self::requireAuth();

        $id      = (int) ($_POST['id'] ?? 0);
        $cliente = Cliente::find($id);

        if ($cliente && $cliente->eliminarCliente()) {
            self::flash('crm_exito', 'Cliente eliminado correctamente.');
        } else {
            self::flash('crm_error', 'No se pudo eliminar el cliente.');
        }

        header('Location: /crm');
        exit;
    }
}

<?php
namespace Controllers;

use Model\Tenant;
use MVC\Router;

class RegistroController {
    public static function registro(Router $router) {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre     = trim($_POST['nombre'] ?? '');
            $subdominio = strtolower(trim($_POST['subdominio'] ?? ''));
            $email      = trim($_POST['email'] ?? '');
            $password   = $_POST['password'] ?? '';

            // Validaciones centralizadas en el modelo (Tarea 4):
            // las mismas reglas que usa el panel superadmin.
            $errores = Tenant::validarAlta($nombre, $subdominio, $email, $password);

            if (empty($errores)) {
                try {
                    // Alta transaccional: tenant + primer admin (estado 'prueba')
                    Tenant::altaConAdmin($nombre, $subdominio, $email, $password);

                    // Éxito → redirigir al sitio de su subdominio
                    //   producción: https://{$subdominio}.tudominio.com/
                    //   desarrollo: /?tenant={$subdominio}
                    header("Location: /?tenant={$subdominio}");
                    exit;

                } catch (\Throwable $e) {
                    $errores[] = 'No se pudo completar el registro. Intenta de nuevo.';
                }
            }
        }

        $router->render('registro/form-registro', [
            'errores'    => $errores,
            'nombre'     => $_POST['nombre'] ?? '',
            'subdominio' => $_POST['subdominio'] ?? '',
            'email'      => $_POST['email'] ?? '',
        ]);
    }
}

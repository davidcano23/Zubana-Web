<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;

class LoginController {

   public static function login(\MVC\Router $router) {
    $errores = [];

    // Detecta si viene del modal (fetch) o de una petición normal
    $isAjax = (
        (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
        (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth = new \Model\Admin($_POST);
        $errores = $auth->validar();

        if (empty($errores)) {
            $resultado = $auth->existeUsuario();

            if (!$resultado) {
                $errores = \Model\Admin::getErrores();
            } else {
                $autenticado = $auth->comprobarPassword($resultado);

                if ($autenticado) {
                    // Crea la sesión sin redirigir aquí
                    $auth->iniciarSesion();

                    if ($isAjax) {
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode(['ok' => true, 'redirect' => '/' ]);
                        return;
                    } else {
                        // Si alguien envía /login normal (no-ajax) -> vuelve al home con sesión
                        header('Location: /');
                        exit;
                    }
                } else {
                    $errores = \Model\Admin::getErrores();
                }
            }
        }

        // Respuesta JSON para el modal con errores
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'errors' => array_values($errores)]);
            return;
        }
    }

    // Render legacy de /login (por si alguien entra directo)
    $router->render('auth/login', [
        'errores' => $errores
    ]);
}


    public static function logout() {
        session_start();
        $_SESSION = [];

        header('location: /');
    }
}
<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }
    public function comprobarRutas() {

        session_start();

        $auth = $_SESSION['login'] ?? null;

        // Arreglo de rutas protegidas
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
            '/propiedades/eliminar'
        ];

        // --- CORRECCIÓN PARA HOSTINGER ---
        // Intentamos leer PATH_INFO
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';

        // Si PATH_INFO no existe o es solo un slash, intentamos obtener la ruta desde REQUEST_URI
        if (!isset($_SERVER['PATH_INFO']) || $urlActual === '/') {
            // Obtenemos la URL completa (ej: /propiedad?id=1)
            $requestUri = $_SERVER['REQUEST_URI']; 
            
            // Quitamos los parámetros GET (lo que va después del ?)
            $posicionInterrogacion = strpos($requestUri, '?');
            
            if ($posicionInterrogacion !== false) {
                $urlActual = substr($requestUri, 0, $posicionInterrogacion);
            } else {
                $urlActual = $requestUri;
            }
        }
        // ---------------------------------

        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // Proteger la ruta
        if(in_array($urlActual, $rutas_protegidas ) && !$auth) {
            header('location: /');
        }

        if($fn) {
            call_user_func($fn, $this);
        } else {
            echo "pagina no encontrada";
        }
    }

    public function render($view, $datos = []) {
        foreach($datos as $key => $value) {
            $$key = $value;
        } 

        ob_start(); //Almacenamiento en memoria durante un momento...

        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); //Limpia el buffer
        include_once __DIR__ . '/views/layout.php';
    }
}

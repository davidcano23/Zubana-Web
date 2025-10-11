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

        //Arreglo de rutas protegidas
        $rutas_protegidas = [
            '/admin',
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


        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        //Proteger la ruta
        if(in_array($urlActual, $rutas_protegidas ) && !$auth) {
            header('location: /');
        }

        if($fn) {
            //La URL existe y hay una funcion asociada
            // debuguear($fn);
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

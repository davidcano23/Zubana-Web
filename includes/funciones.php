<?php

define('TEMPLATE_URL', __DIR__.'/template');
define('FUNCIONES_URL',__DIR__.'funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/'); 

function incluirTemplate( string $nombre, bool $inicio = false) {
    include TEMPLATE_URL. "/{$nombre}.php";
}

function estaAutenticado() {
    session_start();

    if(!$_SESSION['login']) {
        header('location: /');
    }
}


function debuguear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";

    exit;
}

//ESCAPA / SANETIZAR EL HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//VALIDAR TIPO DE CONTENIDO
function validarTipoContenido($tipo) {
    $tipos = ['vendedor' , 'propiedad'];

    return in_array($tipo, $tipos);
}

//Muestra los mensajes
function mostrarNotificacion($codigo) {
    $mensaje = '';

    switch($codigo) {
        case 1: 
            $mensaje = 'Creado Correctamente';
            break;
        case 2: 
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3: 
            $mensaje = 'Eliminado Correctamente';
            break;

            default: 
            $mensaje = false;
            break;
    }

    return $mensaje;
}


function validarORedireccion(string $url) {
    //Validar el ID 
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header("location: {$url}");
    }

    return $id;
}
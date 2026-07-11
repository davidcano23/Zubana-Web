<?php

define('TEMPLATE_URL', __DIR__.'/template');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');

function carpetaImagenes(): string {
    $ruta = __DIR__ . '/../public/imagenes/' . \Model\ActiveRecord::getTenant() . '/';
    if (!is_dir($ruta)) { mkdir($ruta, 0755, true); }
    return $ruta;
}

function urlImagen(string $archivo): string {
    return '/imagenes/' . \Model\ActiveRecord::getTenant() . '/' . $archivo;
}

function estaAutenticado() {
    session_start();

    if (empty($_SESSION['login'])) {
        header('location: /');
        exit;
    }
}

function debuguear(mixed $variable): void {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s(string $html): string {
    return htmlspecialchars($html);
}

function mostrarNotificacion(int $codigo): string|false {
    switch ($codigo) {
        case 1: return 'Creado Correctamente';
        case 2: return 'Actualizado Correctamente';
        case 3: return 'Eliminado Correctamente';
        default: return false;
    }
}

function validarORedireccion(string $url): int {
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    if (!$id) {
        header("location: {$url}");
        exit;
    }

    return $id;
}

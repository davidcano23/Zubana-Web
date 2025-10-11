<?php
<<<<<<< HEAD
=======
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Normaliza PATH_INFO/url cuando vienen desde .htaccess
 * (En algunos servidores PATH_INFO llega vacío)
 */
if (!isset($_SERVER['PATH_INFO']) || $_SERVER['PATH_INFO'] === '') {
    if (isset($_GET['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = $_GET['PATH_INFO'];
    } elseif (isset($_GET['url'])) {
        $_SERVER['PATH_INFO'] = $_GET['url'];
    } else {
        $_SERVER['PATH_INFO'] = '/';
    }
}

// Si tu Router usa $_GET['url'], asegúralo también:
if (!isset($_GET['url']) && isset($_SERVER['PATH_INFO'])) {
    $_GET['url'] = $_SERVER['PATH_INFO'];
}

>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/app.php';

use Controllers\ApartamentoController;
use Controllers\FincaController;
use Controllers\LocalController;
use MVC\Router;
use Controllers\PaginaController;
use Controllers\LoginController;
use Controllers\LotesController;
use Controllers\PropiedadController;

$router = new Router();

//ZONA PUBLICA
$router->get('/', [PaginaController::class, 'index']);
$router->get('/propiedad', [PaginaController::class, 'propiedad']);

//ZONA PRIVADA
$router->get('/admin', [PropiedadController::class, 'admin']);
$router->get('/tipo-propiedad', [PropiedadController::class, 'tipoPropiedad']);

//PROPIEDADES
$router->get('/propiedades/crear-casa', [PropiedadController::class, 'crearCasa']);
$router->post('/propiedades/crear-casa', [PropiedadController::class, 'crearCasa']);
$router->get('/propiedades/actualizar-casa', [PropiedadController::class, 'actualizarCasa']);
$router->post('/propiedades/actualizar-casa', [PropiedadController::class, 'actualizarCasa']);

//FINCA
$router->get('/propiedades/crear-finca', [FincaController::class, 'crearFinca']);
$router->post('/propiedades/crear-finca', [FincaController::class, 'crearFinca']);
$router->get('/propiedades/actualizar-finca', [FincaController::class, 'actualizarFinca']);
$router->post('/propiedades/actualizar-finca', [FincaController::class, 'actualizarFinca']);

//APARTAMENTOS
$router->get('/propiedades/crear-apartamento', [ApartamentoController::class, 'crearApartamento']);
$router->post('/propiedades/crear-apartamento', [ApartamentoController::class, 'crearApartamento']);
$router->get('/propiedades/actualizar-apartamento', [ApartamentoController::class, 'actualizarApartamento']);
$router->post('/propiedades/actualizar-apartamento', [ApartamentoController::class, 'actualizarApartamento']);

//LOTES
$router->get('/propiedades/crear-lote', [LotesController::class, 'crearLotes']);
$router->post('/propiedades/crear-lote', [LotesController::class, 'crearLotes']);
$router->get('/propiedades/actualizar-lote', [LotesController::class, 'actualizarLotes']);
$router->post('/propiedades/actualizar-lote', [LotesController::class, 'actualizarLotes']);

//LOCAL
$router->get('/propiedades/crear-local', [LocalController::class, 'crearLocal']);
$router->post('/propiedades/crear-local', [LocalController::class, 'crearLocal']);
$router->get('/propiedades/actualizar-local', [LocalController::class, 'actualizarLocal']);
$router->post('/propiedades/actualizar-local', [LocalController::class, 'actualizarLocal']);

$router->post('/propiedades/eliminar', [PropiedadController::class, 'eliminar']);

<<<<<<< HEAD

=======
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
//Login y Autenticacion
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

<<<<<<< HEAD

$router->comprobarRutas();

?>

=======
$router->comprobarRutas();
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189

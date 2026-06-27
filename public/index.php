<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/app.php';

use Controllers\ApartamentoController;
use Controllers\ApiBusquedaController;
use Controllers\ApiPropiedadesController;
use Controllers\AvisoPoliticasController;
use Controllers\ConfiguracionesController;
use Controllers\CrmController;
use Controllers\WhatsAppController;
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

//AVISO LEGAL Y POLITICAS DE PRIVACIDAD
$router->get('/aviso-legal', [AvisoPoliticasController::class, 'avisoLegal'] );
$router->get('/politica-de-privacidad', [AvisoPoliticasController::class, 'politicasPrivacidad' ]);

$router->post('/propiedades/eliminar', [PropiedadController::class, 'eliminar']);

//WHATSAPP WEBHOOK (público — Meta necesita acceso sin auth)
$router->get('/webhook/whatsapp',  [WhatsAppController::class, 'verificar']);
$router->post('/webhook/whatsapp', [WhatsAppController::class, 'recibir']);
$router->get('/crm/whatsapp',      [WhatsAppController::class, 'configuracion']);

//CRM
$router->get('/crm',             [CrmController::class, 'index']);
$router->get('/crm/crear',       [CrmController::class, 'crear']);
$router->post('/crm/crear',      [CrmController::class, 'crear']);
$router->get('/crm/cliente',     [CrmController::class, 'detalle']);
$router->get('/crm/editar',      [CrmController::class, 'editar']);
$router->post('/crm/editar',     [CrmController::class, 'editar']);
$router->post('/crm/actividad',  [CrmController::class, 'actividad']);
$router->post('/crm/estado',     [CrmController::class, 'estado']);
$router->post('/crm/eliminar',   [CrmController::class, 'eliminar']);

//CONFIGURACIONES
$router->get('/configuraciones', [ConfiguracionesController::class, 'index']);
$router->post('/configuraciones/guardar', [ConfiguracionesController::class, 'guardar']);
$router->post('/configuraciones/reset', [ConfiguracionesController::class, 'resetear']);

//Login y Autenticacion
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

//API DE BUSQUEDA
$router->get('/api/buscar', [ApiBusquedaController::class, 'buscar']);
// $router->get('/api/propiedades', [ApiPropiedadesController::class, 'index']);


$router->comprobarRutas();

?>



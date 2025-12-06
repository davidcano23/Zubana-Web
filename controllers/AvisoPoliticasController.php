<?php

namespace Controllers;

use MVC\Router;

class AvisoPoliticasController {

    public static function avisoLegal(Router $router) {


        $router->render('politicas/aviso-legal', [
        
    ]);
    }

    public static function politicasPrivacidad(Router $router) {


        $router->render('politicas/politica-de-privacidad', [
        
    ]);
    }


}
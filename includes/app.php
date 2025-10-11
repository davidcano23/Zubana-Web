<?php

require 'funciones.php';
require 'config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';



//Conectarnos a la base de datos
    $db = conectarDB();


use Model\ActiveRecord;

ActiveRecord::setDB($db);


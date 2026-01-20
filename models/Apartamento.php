<?php

namespace Model;

class Apartamento extends ActiveRecord {
    
    protected static $tabla = 'apartamento';

    protected static $columnasDB = ['id', 'nombre', 'precio', 'ubicacion', 'direccion', 'imagen', 'propietario', 'contacto', 'modalidad', 'codigo','area_total', 'habitaciones', 'banos', 'zona_ropa', 'cocina', 'sala_comedor', 'balcon', 'estrato', 'garaje', 'tipo_unidad', 'tipo', 'vigilancia', 'zonas_verdes', 'juegos', 'coworking', 'gimnasio', 'piscina', 'cancha', 'actualizacion','descripcion', 'barrio','administracion', 'corregimiento', 'palabra_clave', 'latitud', 'longitud'];

    public $id;
    public $nombre;
    public $precio;
    public $ubicacion;
    public $direccion;
    public $imagen;
    public $propietario;
    public $contacto;
    public $modalidad;
    public $codigo;
    public $area_total;
    public $habitaciones;
    public $banos;
    public $zona_ropa;
    public $cocina;
    public $sala_comedor;
    public $balcon;
    public $estrato;
    public $garaje;
    public $tipo_unidad;
    public $tipo;
    public $vigilancia;
    public $zonas_verdes;
    public $juegos;
    public $coworking;
    public $gimnasio;
    public $piscina;
    public $cancha;
    public $actualizacion;
    public $descripcion;
    public $barrio;
    public $administracion;
    public $corregimiento;
    public $palabra_clave;
    public $latitud;
    public $longitud;

    //Definir la conexion a la BD
    public static function setDB($database) {
        self::$db = $database;
    }

    public function __construct($args = []) {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->precio = $args['precio'] ?? '';
    $this->ubicacion = $args['ubicacion'] ?? '';
    $this->direccion = $args['direccion'] ?? '';
    $this->imagen = $args['imagen'] ?? '';
    $this->propietario = $args['propietario'] ?? '';
    $this->contacto = $args['contacto'] ?? '';
    $this->modalidad = $args['modalidad'] ?? '';
    $this->codigo = $args['codigo'] ?? '';
    $this->area_total = $args['area_total'] ?? '';
    $this->habitaciones = $args['habitaciones'] ?? '';
    $this->banos = $args['banos'] ?? '';
    $this->zona_ropa = $args['zona_ropa'] ?? '';
    $this->cocina = $args['cocina'] ?? '';
    $this->sala_comedor = $args['sala_comedor'] ?? '';
    $this->balcon = $args['balcon'] ?? '';
    $this->estrato = $args['estrato'] ?? '';
    $this->garaje = $args['garaje'] ?? '';
    $this->tipo_unidad = $args['tipo_unidad'] ?? '';
    $this->tipo = $args['tipo'] ?? '';
    $this->vigilancia = $args['vigilancia'] ?? '';
    $this->zonas_verdes = $args['zonas_verdes'] ?? '';
    $this->juegos = $args['juegos'] ?? '';
    $this->coworking = $args['coworking'] ?? '';
    $this->gimnasio = $args['gimnasio'] ?? '';
    $this->piscina = $args['piscina'] ?? '';
    $this->cancha = $args['cancha'] ?? '';
    $this->actualizacion = $args['actualizacion'] ?? '';
    $this->descripcion = $args['descripcion'] ?? '';
    $this->barrio = $args['barrio'] ?? '';
    $this->administracion = $args['administracion'] ?? '';
    $this->corregimiento = $args['corregimiento'] ?? '';
    $this->palabra_clave = $args['palabra_clave'] ?? '';
    $this->latitud = $args['latitud'] ?? 0;
    $this->longitud = $args['longitud'] ?? 0;
    }


    public function validar() {
        if(!isset($this->precio) || $this->precio === '') {
            self::$errores[] = "El campo precio es obligatorio";
        }
        if(!$this->ubicacion) {
            self::$errores[] = "El campo ubicacion es obligatorio";
        }
        if(strlen($this->ubicacion) > 119 ) {
            self::$errores[] = "El campo ubicacion no puede superar los 119 caracteres";
        }
        if(!$this->direccion) {
            $this->direccion = "N/A";
        }
        if(strlen($this->direccion) > 74 ) {
            self::$errores[] = "El campo direccion no puede superar los 74 caracteres";
        }
        if(!$this->imagen) {
            self::$errores[] = "El campo imagen es obligatorio";
        }
        if(!$this->propietario) {
            $this->propietario = "N/A";
        }
        if(strlen($this->propietario) > 100 ) {
            self::$errores[] = "El campo propietario no puede superar los 100 caracteres";
        }
        if(!isset($this->contacto) || $this->contacto === '') {
            $this->contacto = "N/A";
        }
        if(strlen($this->contacto) > 15 ) {
            self::$errores[] = "El campo contacto no puede superar los 15 caracteres";
        }
        if(!$this->modalidad) {
            self::$errores[] = "El campo modalidad es obligatorio";
        }
        if(!isset($this->area_total) || $this->area_total === '') {
            $this->area_total = 0;
        }
        if(strlen($this->area_total) > 15 ) {
            self::$errores[] = "El campo area total no puede superar los 15 caracteres";
        }
        if(!isset($this->habitaciones) || $this->habitaciones === '') {
            $this->habitaciones = 0;
        }
        if(!isset($this->banos) || $this->banos === '') {
            $this->banos = 0;
        }
        if(!isset($this->estrato) || $this->estrato === '') {
            $this->estrato = 0;
        }
        if(!$this->tipo_unidad) {
            self::$errores[] = "El campo tipo_unidad es obligatoria";
        }
        if(!$this->tipo) {
            self::$errores[] = "El campo tipo es obligatoria";
        }
        if(!$this->zona_ropa) {
            $this->zona_ropa = "NO";
        }

        if(!$this->cocina) {
            $this->cocina = "NO";
        }

        if(!$this->sala_comedor) {
            $this->sala_comedor = "NO";
        }

        if(!$this->balcon) {
            $this->balcon = "NO";
        }

        if(!$this->garaje) {
            $this->garaje = "NO";
        }

        if(!$this->vigilancia) {
            $this->vigilancia = "NO";
        }

        if(!$this->zonas_verdes) {
            $this->zonas_verdes = "NO";
        }

        if(!$this->juegos) {
            $this->juegos = "NO";
        }

        if(!$this->coworking) {
            $this->coworking = "NO";
        }

        if(!$this->gimnasio) {
            $this->gimnasio = "NO";
        }

        if(!$this->piscina) {
            $this->piscina = "NO";
        }

        if(!$this->cancha) {
            $this->cancha = "NO";
        }

        if(!$this->actualizacion) {
            self::$errores[] = "El campo actualizacion es obligatoria";
        }
        if(!$this->descripcion) {
            self::$errores[] = "El campo descripcion es obligatoria";
        }
        if(strlen($this->descripcion) < 50) {
            self::$errores[] = "El campo descripcion es obligatorio y debe contener al menos 50 caracteres";
        }
        if(strlen($this->descripcion) > 700) {
            self::$errores[] = "El campo descripcion no puede superar los 700 caracteres";
        }
        if(!$this->barrio) {
            $this->barrio = "N/A";
        }
        if(strlen($this->barrio) > 255) {
            self::$errores[] = "El campo barrio no puede superar los 255 caracteres";
        }
        if(!$this->administracion){
           $this->administracion = 0;
        }
        if(!$this->palabra_clave) {
            $this->palabra_clave = "N/A";
        }
        if(strlen($this->palabra_clave) > 50) {
            self::$errores[] = "La palabra clave no puede superar los 50 caracteres";
        }
        if(!$this->corregimiento) {
            $this->corregimiento = "N/A";
        }
        if(strlen($this->corregimiento) > 50) {
            self::$errores[] = "El corregimiento no puede superar los 50 caracteres";
        }

        return self::$errores;
    }
}


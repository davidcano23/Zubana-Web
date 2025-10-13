<?php

namespace Model;

class Apartamento extends ActiveRecord {
    
    protected static $tabla = 'apartamento';

    protected static $columnasDB = ['id', 'nombre', 'precio', 'ubicacion', 'direccion', 'imagen', 'propietario', 'contacto', 'modalidad', 'codigo','area_total', 'habitaciones', 'banos', 'zona_ropa', 'cocina', 'sala_comedor', 'balcon', 'estrato', 'garaje', 'tipo_unidad', 'tipo', 'vigilancia', 'zonas_verdes', 'juegos', 'coworking', 'gimnasio', 'piscina', 'cancha', 'actualizacion','descripcion', 'barrio'];

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
    }


    public function validar() {
        // if(!$this->nombre) {
        //     self::$errores[] = "El campo nombre es obligatorio";
        // }
        // if(strlen($this->nombre) < 5 ) {
        //     self::$errores[] = "El campo nombre debe contener al menos 5 caracteres";
        // }
        if(strlen($this->nombre) > 100 ) {
            self::$errores[] = "El campo nombre no puede superar los 100 caracteres";
        }
        if(!isset($this->precio) || $this->precio === '') {
            self::$errores[] = "El campo precio es obligatorio";
        }
        if(!$this->ubicacion) {
            self::$errores[] = "El campo ubicacion es obligatorio";
        }
        if(strlen($this->ubicacion) > 119 ) {
            self::$errores[] = "El campo ubicacion no puede superar los 119 caracteres";
        }

        // if(!$this->direccion) {
        //     self::$errores[] = "El campo direccion es obligatorio";
        // }
        if(strlen($this->direccion) > 74 ) {
            self::$errores[] = "El campo direccion no puede superar los 74 caracteres";
        }
        if(!$this->imagen) {
            self::$errores[] = "El campo imagen es obligatorio";
        }
        if(!$this->propietario) {
            self::$errores[] = "El campo propietario es obligatorio";
        }
        if(strlen($this->propietario) > 100 ) {
            self::$errores[] = "El campo propietario no puede superar los 100 caracteres";
        }
        if(!isset($this->contacto) || $this->contacto === '') {
            self::$errores[] = "El campo contacto es obligatorio";
        }
        if(strlen($this->contacto) > 15 ) {
            self::$errores[] = "El campo contacto no puede superar los 15 caracteres";
        }
        if(!$this->modalidad) {
            self::$errores[] = "El campo modalidad es obligatorio";
        }
        // if(!$this->codigo) {
        //     self::$errores[] = "El campo codigo es obligatorio";
        // }
        // if(strlen($this->codigo) > 5 ) {
        //     self::$errores[] = "El campo codigo no puede superar los 5 caracteres";
        // }
        if(!isset($this->area_total) || $this->area_total === '') {
            self::$errores[] = "La area es obligatoria";
        }
        if(strlen($this->area_total) > 15 ) {
            self::$errores[] = "El campo area total no puede superar los 15 caracteres";
        }
        if(!isset($this->habitaciones) || $this->habitaciones === '') {
            self::$errores[] = "La habitaciones es obligatoria";
        }
        if(!isset($this->banos) || $this->banos === '') {
            self::$errores[] = "El campo baños es obligatoria";
        }
        if(!$this->zona_ropa) {
            self::$errores[] = "El campo zona_ropa es obligatoria";
        }
        if(!$this->cocina) {
            self::$errores[] = "El campo cocina es obligatoria";
        }
        if(!$this->sala_comedor) {
            self::$errores[] = "El campo sala_comedor es obligatoria";
        }
        if(!$this->balcon) {
            self::$errores[] = "El campo balcon es obligatoria";
        }
        if(!isset($this->estrato) || $this->estrato === '') {
            self::$errores[] = "El campo estrato es obligatoria";
        }
        if(!$this->garaje) {
            self::$errores[] = "El campo garaje es obligatoria";
        }
        if(!$this->tipo_unidad) {
            self::$errores[] = "El campo tipo_unidad es obligatoria";
        }
        if(!$this->tipo) {
            self::$errores[] = "El campo tipo es obligatoria";
        }
        if(!$this->vigilancia) {
            self::$errores[] = "El campo Vigilancia es obligatoria";
        }
        if(!$this->zonas_verdes) {
            self::$errores[] = "El campo Zonas Verdes es obligatoria";
        }
        if(!$this->juegos) {
            self::$errores[] = "El campo Juegos Infantiles es obligatoria";
        }
        if(!$this->coworking) {
            self::$errores[] = "El campo Coworking es obligatoria";
        }
        if(!$this->gimnasio) {
            self::$errores[] = "El campo Gimnasio es obligatoria";
        }
        if(!$this->piscina) {
            self::$errores[] = "El campo Piscina es obligatoria";
        }
        if(!$this->cancha) {
            self::$errores[] = "El campo Cancha Deportivas es obligatoria";
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
        if(strlen($this->descripcion) > 500) {
            self::$errores[] = "El campo descripcion no puede superar los 500 caracteres";
        }
        if(!$this->barrio) {
            self::$errores[] = "El campo barrio es obligatoria";
        }
        if(strlen($this->barrio) > 255) {
            self::$errores[] = "El campo barrio no puede superar los 255 caracteres";
        }

        return self::$errores;
    }
}


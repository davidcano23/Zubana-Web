<?php

namespace Model;

class Lote extends ActiveRecord {
    
    protected static $tabla = 'lotes';

    protected static $columnasDB = ['id', 'nombre', 'precio', 'ubicacion', 'direccion', 'imagen', 'propietario', 'contacto', 'modalidad', 'codigo','area_total', 'estrato','tipo_unidad', 'tipo','actualizacion','descripcion','barrio', 'administracion', 'corregimiento', 'palabra_clave', 'latitud', 'longitud'];

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
    public $estrato;
    public $tipo_unidad;
    public $tipo;
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
    $this->estrato = $args['estrato'] ?? '';
    $this->tipo_unidad = $args['tipo_unidad'] ?? '';
    $this->tipo = $args['tipo'] ?? '';
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
            self::$errores[] = "La area es obligatoria";
        }
        if(strlen($this->area_total) > 25 ) {
            self::$errores[] = "El campo area total no puede superar los 25 caracteres";
        }
        if(!isset($this->estrato) || $this->estrato === '') {
            self::$errores[] = "El campo estrato es obligatoria";
        }
        if(!$this->tipo_unidad) {
            self::$errores[] = "El campo tipo de unidad es obligatoria";
        }
        if(!$this->tipo) {
            self::$errores[] = "El campo tipo de Propieadad es obligatoria";
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


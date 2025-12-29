<?php

namespace Model;

class Casa extends ActiveRecord {
    
    protected static $tabla = 'casa';

    protected static $columnasDB = ['id', 'area_total', 'habitaciones', 'sala', 'zona_ropa', 'banos', 'nombre', 'imagen', 'precio','ubicacion', 'direccion', 'tipo', 'propietario', 'contacto', 'codigo', 'modalidad', 'area_construida', 'estrato', 'cocina', 'garaje', 'tipo_unidad', 'vigilancia', 'zonas_verdes', 'juegos', 'coworking', 'gimnasio', 'piscina', 'cancha','actualizacion','descripcion','barrio', 'administracion', 'corregimiento', 'palabra_clave', 'latitud', 'longitud'];

    public $id;
    public $area_total;
    public $habitaciones;
    public $sala;
    public $zona_ropa;
    public $banos;
    public $nombre;
    public $imagen;
    public $precio;
    public $ubicacion;
    public $direccion;
    public $tipo;
    public $propietario;
    public $contacto;
    public $codigo;
    public $modalidad;
    public $area_construida;
    public $estrato;
    public $cocina;
    public $garaje;
    public $tipo_unidad;
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
    $this->area_total = $args['area_total'] ?? '';
    $this->habitaciones = $args['habitaciones'] ?? '';
    $this->sala = $args['sala'] ?? '';
    $this->zona_ropa = $args['zona_ropa'] ?? '';
    $this->banos = $args['banos'] ?? '';
    $this->nombre = $args['nombre'] ?? '';
    $this->imagen = $args['imagen'] ?? '';
    $this->precio = $args['precio'] ?? '';
    $this->ubicacion = $args['ubicacion'] ?? '';
    $this->direccion = $args['direccion'] ?? '';
    $this->tipo = $args['tipo'] ?? '';
    $this->propietario = $args['propietario'] ?? '';
    $this->contacto = $args['contacto'] ?? '';
    $this->codigo = $args['codigo'] ?? '';
    $this->modalidad = $args['modalidad'] ?? '';
    $this->area_construida = $args['area_construida'] ?? '';
    $this->estrato = $args['estrato'] ?? '';
    $this->cocina = $args['cocina'] ?? '';
    $this->garaje = $args['garaje'] ?? '';
    $this->tipo_unidad = $args['tipo_unidad'] ?? '';
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
        if(!isset($this->area_total) || $this->area_total === '') {
            self::$errores[] = "El campo area es obligatorio";
        }
        if(strlen($this->area_total) > 24 ) {
            self::$errores[] = "El campo area no puede superar los 24 caracteres";
        }
        if(!isset($this->habitaciones) || $this->habitaciones === '') {
            self::$errores[] = "El campo habitaciones es obligatorio";
        }
        if($this->habitaciones > 15 ) {
            self::$errores[] = "El campo habitaciones no puede superar los 15 caracteres";
        }
        if(!$this->sala) {
            self::$errores[] = "El campo sala es obligatorio";
        }
        if(!$this->zona_ropa) {
            self::$errores[] = "El campo lavado es obligatorio";
        }
        if(!isset($this->banos) || $this->banos === '') {
            self::$errores[] = "El campo baños es obligatorio";
        }

        // if(!$this->nombre) {
        //     self::$errores[] = "El campo nombre es obligatorio";
        // }
        // if(strlen($this->nombre) < 5 ) {
        //     self::$errores[] = "El campo nombre debe contener al menos 5 caracteres";
        // }
        if(strlen($this->nombre) > 100 ) {
            self::$errores[] = "El campo nombre no puede superar los 100 caracteres";
        }
        if(!$this->imagen) {
            self::$errores[] = "El campo imagen es obligatorio";
        }
        if(!isset($this->precio) || $this->precio === '') {
            self::$errores[] = "El campo precio es obligatorio";
        }
        if(!$this->ubicacion) {
            self::$errores[] = "La ubicacion es obligatoria";
        }
        if(strlen($this->ubicacion) > 119 ) {
            self::$errores[] = "El campo ubicacion no puede superar los 119 caracteres";
        }
        // if(!$this->direccion) {
        //     self::$errores[] = "La direccion es obligatoria";
        // }
        if(strlen($this->direccion) > 59 ) {
            self::$errores[] = "El campo direccion no puede superar los 59 caracteres";
        }
        if(!$this->tipo) {
            self::$errores[] = "El campo tipo es obligatoria";
        }
        if(!$this->propietario) {
            self::$errores[] = "El campo propietario es obligatoria";
        }
        if(strlen($this->propietario) > 100 ) {
            self::$errores[] = "El campo propietario no puede superar los 100 caracteres";
        }
        if(!isset($this->contacto) || $this->contacto === '') {
            self::$errores[] = "El campo contacto es obligatoria";
        }
        if(strlen($this->contacto) > 15 ) {
            self::$errores[] = "El campo contacto no puede superar los 15 caracteres";
        }
        // if(!$this->codigo) {
        //     self::$errores[] = "El campo codigo es obligatoria";
        // }
        // if(strlen($this->codigo) > 5 ) {
        //     self::$errores[] = "El campo codigo no puede superar los 5 caracteres";
        // }
        if(!$this->modalidad) {
            self::$errores[] = "El campo modalidad es obligatoria";
        }
        if(!isset($this->area_construida) || $this->area_construida === '') {
            self::$errores[] = "El campo area construida es obligatoria";
        }
        if(strlen($this->area_construida) > 25 ) {
            self::$errores[] = "El campo area construida no puede superar los 25 caracteres";
        }
        if(!isset($this->estrato) || $this->estrato === '') {
            self::$errores[] = "El campo estrato es obligatoria";
        }
        if(!$this->cocina) {
            self::$errores[] = "El campo cocina es obligatoria";
        }
        if(!$this->garaje) {
            self::$errores[] = "El campo garaje es obligatoria";
        }
        if(!$this->tipo_unidad) {
            self::$errores[] = "El campo tipo de unidad es obligatoria";
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
        if(strlen($this->descripcion) > 700) {
            self::$errores[] = "El campo descripcion no puede superar los 500 caracteres";
        }
        if(!$this->barrio) {
            self::$errores[] = "El campo barrio es obligatoria";
        }
        if(strlen($this->barrio) > 255) {
            self::$errores[] = "El campo barrio no puede superar los 255 caracteres";
        }
        if(!$this->administracion){
            $this->administracion = 0;
        }

        return self::$errores;
    }
}


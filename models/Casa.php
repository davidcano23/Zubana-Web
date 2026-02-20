<?php

namespace Model;

class Casa extends ActiveRecord {

    protected static $tabla = 'casa';

    protected static $columnasDB = [
        'id', 'nombre', 'precio', 'ubicacion', 'direccion', 'imagen',
        'propietario', 'contacto', 'modalidad', 'codigo',
        'area_total', 'area_construida', 'habitaciones', 'banos',
        'sala', 'zona_ropa', 'cocina', 'estrato', 'garaje',
        'tipo_unidad', 'tipo',
        'vigilancia', 'zonas_verdes', 'juegos', 'coworking',
        'gimnasio', 'piscina', 'cancha',
        'actualizacion', 'descripcion', 'barrio', 'administracion',
        'corregimiento', 'palabra_clave', 'latitud', 'longitud', 'jacuzzi', 'turco'
    ];

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
    public $area_construida;
    public $habitaciones;
    public $banos;
    public $sala;
    public $zona_ropa;
    public $cocina;
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
    public $jacuzzi;
    public $turco;

    public static function setDB($database) {
        self::$db = $database;
    }

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;

        $this->nombre = trim((string)($args['nombre'] ?? ''));
        $this->precio = trim((string)($args['precio'] ?? ''));
        $this->ubicacion = trim((string)($args['ubicacion'] ?? ''));
        $this->direccion = trim((string)($args['direccion'] ?? ''));
        $this->imagen = trim((string)($args['imagen'] ?? ''));
        $this->propietario = trim((string)($args['propietario'] ?? ''));
        $this->contacto = trim((string)($args['contacto'] ?? ''));
        $this->modalidad = trim((string)($args['modalidad'] ?? ''));
        $this->codigo = trim((string)($args['codigo'] ?? ''));

        $this->area_total = $args['area_total'] ?? '';
        $this->area_construida = $args['area_construida'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->banos = $args['banos'] ?? '';
        $this->estrato = $args['estrato'] ?? '';

        $this->sala = trim((string)($args['sala'] ?? ''));
        $this->zona_ropa = trim((string)($args['zona_ropa'] ?? ''));
        $this->cocina = trim((string)($args['cocina'] ?? ''));
        $this->garaje = trim((string)($args['garaje'] ?? ''));

        $this->tipo_unidad = trim((string)($args['tipo_unidad'] ?? ''));
        $this->tipo = trim((string)($args['tipo'] ?? ''));

        $this->vigilancia = trim((string)($args['vigilancia'] ?? ''));
        $this->zonas_verdes = trim((string)($args['zonas_verdes'] ?? ''));
        $this->juegos = trim((string)($args['juegos'] ?? ''));
        $this->coworking = trim((string)($args['coworking'] ?? ''));
        $this->gimnasio = trim((string)($args['gimnasio'] ?? ''));
        $this->piscina = trim((string)($args['piscina'] ?? ''));
        $this->cancha = trim((string)($args['cancha'] ?? ''));

        $this->actualizacion = trim((string)($args['actualizacion'] ?? ''));
        $this->descripcion = trim((string)($args['descripcion'] ?? ''));
        $this->barrio = trim((string)($args['barrio'] ?? ''));
        $this->administracion = $args['administracion'] ?? '';
        $this->corregimiento = trim((string)($args['corregimiento'] ?? ''));
        $this->palabra_clave = trim((string)($args['palabra_clave'] ?? ''));

        $this->latitud = $args['latitud'] ?? 0;
        $this->longitud = $args['longitud'] ?? 0;

        $this->jacuzzi = trim((string)($args['jacuzzi'] ?? ''));
        $this->turco = trim((string)($args['turco'] ?? ''));
            }

    public function validar() {

        if(!isset($this->precio) || $this->precio === '') {
            self::$errores[] = "El campo precio es obligatorio";
        }

        if(!$this->ubicacion) {
            self::$errores[] = "El campo ubicacion es obligatorio";
        }

        if(strlen($this->ubicacion) > 119) {
            self::$errores[] = "El campo ubicacion no puede superar los 119 caracteres";
        }

        if(!$this->direccion) {
            $this->direccion = "N/A";
        }

        if(strlen($this->direccion) > 74) {
            self::$errores[] = "El campo direccion no puede superar los 74 caracteres";
        }

        if(!$this->imagen) {
            self::$errores[] = "El campo imagen es obligatorio";
        }

        if(!$this->propietario) {
            $this->propietario = "N/A";
        }

        if(!isset($this->contacto) || $this->contacto === '') {
            $this->contacto = "N/A";
        }

        if(!$this->modalidad) {
            self::$errores[] = "El campo modalidad es obligatorio";
        }

        if(!isset($this->area_total) || $this->area_total === '') {
            $this->area_total = 0;
        }

        if(!isset($this->area_construida) || $this->area_construida === '') {
            $this->area_construida = 0;
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
            self::$errores[] = "El campo tipo_unidad es obligatorio";
        }

        if(!$this->tipo) {
            self::$errores[] = "El campo tipo es obligatorio";
        }

        // AMENITIES → NO
        if(!$this->zona_ropa) $this->zona_ropa = "No";
        if(!$this->cocina) $this->cocina = "No";
        if(!$this->sala) $this->sala = "No";
        if(!$this->garaje) $this->garaje = "No";
        if(!$this->vigilancia) $this->vigilancia = "No";
        if(!$this->zonas_verdes) $this->zonas_verdes = "No";
        if(!$this->juegos) $this->juegos = "No";
        if(!$this->coworking) $this->coworking = "No";
        if(!$this->gimnasio) $this->gimnasio = "No";
        if(!$this->piscina) $this->piscina = "No";
        if(!$this->cancha) $this->cancha = "No";
        if(!$this->jacuzzi)  $this->jacuzzi = "No";
        if(!$this->turco)  $this->turco = "No";

        if(!$this->actualizacion) {
            self::$errores[] = "El campo actualizacion es obligatorio";
        }

        if(!$this->descripcion || strlen($this->descripcion) < 50) {
            self::$errores[] = "La descripcion debe tener al menos 50 caracteres";
        }

        if(!$this->barrio) {
            $this->barrio = "N/A";
        }

        if(!$this->administracion) {
            $this->administracion = 0;
        }

        if(!$this->palabra_clave) {
            $this->palabra_clave = "N/A";
        }

        if(!$this->corregimiento) {
            $this->corregimiento = "N/A";
        }

        return self::$errores;
    }
}

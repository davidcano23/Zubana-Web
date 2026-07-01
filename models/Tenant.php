<?php
namespace Model;

class Tenant extends ActiveRecord {
    protected static $tabla = 'tenants';
    protected static $columnasDB = ['id', 'nombre', 'subdominio', 'estado', 'plan_id', 'created_at'];

    public $id;
    public $nombre;
    public $subdominio;
    public $estado;
    public $plan_id;
    public $created_at;

    // Busca un tenant por su subdominio (ej. 'zubana')
    public static function porSubdominio(string $sub): ?object {
        // 1) Sanear: solo letras, números y guion (el Host lo controla el visitante)
        $sub = preg_replace('/[^a-z0-9-]/', '', strtolower($sub));

        // 2) Consulta DIRECTA, sin filtro de tenant
        $query = "SELECT * FROM tenants WHERE subdominio = '{$sub}' LIMIT 1";
        $resultados = self::consultarSQL($query);
        return array_shift($resultados);
    }
}
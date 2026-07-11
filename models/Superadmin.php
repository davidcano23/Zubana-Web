<?php

namespace Model;

/**
 * Superadmin — usuario interno NUESTRO (dueños del SaaS).
 *
 * Hereda de ActiveRecord SOLO para compartir la conexión (self::$db).
 * NO usa crear()/find()/where() del padre porque esos métodos inyectan
 * tenant_id, y la tabla superadmins es global (no pertenece a ningún tenant).
 * Todas sus consultas son propias y con prepared statements.
 */
class Superadmin extends ActiveRecord {

    protected static $tabla = 'superadmins';

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $activo;

    public function __construct($args = []) {
        $this->id       = $args['id'] ?? null;
        $this->nombre   = $args['nombre'] ?? '';
        $this->email    = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->activo   = $args['activo'] ?? 1;
    }

    // Busca un superadmin ACTIVO por email. Devuelve el objeto o null.
    public static function porEmail(string $email): ?Superadmin {
        $query = "SELECT * FROM " . static::$tabla . " WHERE email = ? AND activo = 1 LIMIT 1";
        $stmt  = self::$db->prepare($query);
        if (!$stmt) return null;

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        return $fila ? new self($fila) : null;
    }

    // Compara el password en texto plano contra el hash guardado
    public function comprobarPassword(string $password): bool {
        return password_verify($password, $this->password);
    }

    /**
     * Abre la sesión de superadmin.
     * IMPORTANTE: NO fija $_SESSION['tenant_id'] ni $_SESSION['login'].
     * Así la sesión de superadmin nunca se confunde con la de una
     * inmobiliaria y ActiveRecord no recibe ningún tenant.
     */
    public function iniciarSesion(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);   // evita fijación de sesión
        $_SESSION['superadmin']        = true;
        $_SESSION['superadmin_id']     = $this->id;
        $_SESSION['superadmin_email']  = $this->email;
        $_SESSION['superadmin_nombre'] = $this->nombre;
    }

    // ¿La sesión actual es de superadmin?
    public static function autenticado(): bool {
        return ($_SESSION['superadmin'] ?? false) === true;
    }

    public static function cerrarSesion(): void {
        unset(
            $_SESSION['superadmin'],
            $_SESSION['superadmin_id'],
            $_SESSION['superadmin_email'],
            $_SESSION['superadmin_nombre']
        );
    }
}

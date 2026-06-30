<?php

namespace Model;

class Admin extends ActiveRecord {

    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email', 'password', 'tenant_id'];

    public $id;
    public $email;
    public $password;
    public $tenant_id;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->tenant_id = $args['tenant_id'] ?? null;
    }

    public function validar(): array
    {
        if(!$this->email) {
            self::$errores[] = 'El Email es obligatorio';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = 'El Email no es válido';
        }

        if(!$this->password) {
            self::$errores[] = 'El Password es obligatorio';
        } elseif (strlen($this->password) < 6) {
            self::$errores[] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$errores;
    }


    public function existeUsuario() {
        $tid = self::tid();
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = ? AND tenant_id = ? LIMIT 1";
        $stmt = self::$db->prepare($query);

        if (!$stmt) {
            self::$errores[] = 'Error al preparar la consulta';
            return null;
        }

        $stmt->bind_param('si', $this->email, $tid);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if (!$resultado->num_rows) {
            self::$errores[] = 'El usuario no existe';
            return null;
        }

        return $resultado;
    }


    public function comprobarPassword($resultado) {
        $usuario = $resultado->fetch_object();

        if (!$usuario) {
            self::$errores[] = 'Error al obtener los datos del usuario';
            return false;
        }

        if (!isset($usuario->password)) {
            self::$errores[] = 'El campo password no está definido en la base de datos';
            return false;
        }

        $autenticado = password_verify($this->password, $usuario->password);

        if (!$autenticado) {
            self::$errores[] = 'El password es incorrecto';
        } else {
            // Guardar el tenant del registro para que iniciarSesion() lo persista
            $this->tenant_id = $usuario->tenant_id ?? null;
        }

        return $autenticado;
    }


        // NUEVO: solo crea sesión (sin redirigir)
        public function iniciarSesion(): void {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['usuario']   = $this->email ?? null;
            $_SESSION['login']     = true;
            $_SESSION['tenant_id'] = $this->tenant_id;
        }

        // (Opcional legado) si algún lugar aún llama a autenticarTo, lo dejamos,
        // pero internamente ya no forzamos redirección en flujos AJAX.
        public function autenticarTo(string $redirect = '/admin'): void {
            $this->iniciarSesion();
            header('Location: ' . $redirect);
            exit;
        }




}
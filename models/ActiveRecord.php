<?php 

namespace Model;

class ActiveRecord {
    //BASE DE DATOS
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //ERRORES
    public static $errores = [];

    //DEFINIR LA CONEXION A LA DATABASE
    public static function setDB($database) {
        self::$db = $database;
    }

public function guardar() {
    if(!is_null($this->{'id'})) {
        //Actualizar
        $this->actualizar();
    } else {
        //Creamos un nuevo registro
        $this->crear();
    }
}


public function crear() {

    //Sanetizar los datos antes de enviarlos a la base de datos
    $atributos = $this->sanetizarAtributos();

        //Insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";
        $resultado = self::$db->query($query);

        //MENSAJE DE EXITO
        if($resultado) {
            $this->{'id'} = self::$db->insert_id; 
            header('location: /admin?Resultado=1');
        }
}

public function actualizar() {
    //Sanetizar los datos antes de enviarlos a la base de datos
    $atributos = $this->sanetizarAtributos();

    $valores = [];

    foreach($atributos as $key => $value) {
        $valores[] = "{$key}='{$value}'";
    }

    $query = "UPDATE " . static::$tabla . " SET ";
    $query .=  join(', ', $valores);
    $query .= " WHERE id = '". self::$db->escape_string($this->{'id'}). "' ";
    $query .= " LIMIT 1 ";
    

    $resultado = self::$db->query($query);

    if($resultado) {
            header('location: /admin?Resultado=2');
        }
}

//ELIMINAR UN REGISTRO
    public function eliminar() {
        //Eliminar propiedad 
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'}) . " LIMIT 1";

    $resultado = self::$db->query($query);

    if($resultado) {
        $this->borrarImagen();
            header('location: /admin?Resultado=3');
        }
    }

    public function eliminarImagenes() {
        //Eliminar propiedad 
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'});

        $resultado = self::$db->query($query);

            if ($resultado) {
                $this->borrarImagen(); // Elimina la imagen del disco
            }

            return $resultado;
    }


//Identificar y unir los atributos d ela BD
public function atributos() {
    $atributos = [];
    foreach (static::$columnasDB as $columna) {
        if($columna === 'id') continue;
        $atributos[$columna] = $this->$columna;
    }
    return $atributos;
}

public function sanetizarAtributos() {
    $atributos = $this->atributos();
    $sanitizado = [];   

    foreach($atributos as $key => $value) {
        $sanitizado[$key] = self::$db->escape_string($value) ;
    }

    return $sanitizado;
}

    //VALIDACION
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    public function setImagen($imagen) {
        //Elimina la imagen previa
        if(!is_null($this->{'id'})) {
            $this->borrarImagen();
                }

        if($imagen) {
            $this->{'imagen'} = $imagen;
        }
    }


    //ElIMINAR ARCHIVO
    public function borrarImagen() {
        //Comprobar si existe el archivo
            $existeArchivo = file_exists(CARPETA_IMAGENES . $this->{'imagen'});
            if($existeArchivo) {
                unlink(CARPETA_IMAGENES . $this->{'imagen'});
            }
    }

        // En modelos/Propiedad.php
<<<<<<< HEAD
    public static function filtrar($filtros) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE 1=1";

        // Filtro por ciudad
        if (!empty($filtros['ciudad'])) {
            $ciudad = self::$db->escape_string($filtros['ciudad']);
            $query .= " AND ciudad LIKE '%$ciudad%'";
        }

        // Filtro por tipo (maneja uno o varios)
        if (!empty($filtros['tipos_array'])) {
            $tipos = array_map(fn($t) => "'" . self::$db->escape_string($t) . "'", $filtros['tipos_array']);
            $query .= " AND tipo IN (" . implode(',', $tipos) . ")";
        } elseif (!empty($filtros['tipo'])) {
            $tipo = self::$db->escape_string($filtros['tipo']);
            $query .= " AND tipo = '$tipo'";
        }

        // Filtros de precios
        if (!empty($filtros['precio_min'])) {
            $query .= " AND precio >= " . (int)$filtros['precio_min'];
        }
        if (!empty($filtros['precio_max'])) {
            $query .= " AND precio <= " . (int)$filtros['precio_max'];
        }

        // Filtros adicionales
        if (!empty($filtros['banos'])) {
            $query .= " AND banos >= " . (int)$filtros['banos'];
        }
        if (!empty($filtros['habitaciones'])) {
            $query .= " AND habitaciones >= " . (int)$filtros['habitaciones'];
        }
        if (!empty($filtros['area_minima'])) {
            $query .= " AND area >= " . (int)$filtros['area_minima'];
        }
        if (!empty($filtros['barrio'])) {
            $barrio = self::$db->escape_string($filtros['barrio']);
            $query .= " AND barrio LIKE '%$barrio%'";
        }

        // Ejecutar y retornar
        return self::consultarSQL($query);
    }


=======
    public static function filtrar($filtros = []) {
        $db = self::$db; // Asegúrate de tener $db definido correctamente

        $ubicacion = mysqli_real_escape_string($db, $filtros['ciudad'] ?? '');
        $tipo = mysqli_real_escape_string($db, $filtros['tipo'] ?? '');
        $precioMin = intval(str_replace('.', '', $filtros['precio_min'] ?? 0));
        $precioMax = intval(str_replace('.', '', $filtros['precio_max'] ?? 0));
        $banos = intval($filtros['banos'] ?? 0);
        $habitaciones = intval($filtros['habitaciones'] ?? 0);
        $area_minima = intval($filtros['area_minima'] ?? 0);
        $modalidad_filtros = mysqli_real_escape_string($db, $filtros['modalidad_filtros'] ?? '');
        $tipo_unidad_filtros = mysqli_real_escape_string($db, $filtros['tipo_unidad_filtros'] ?? '');
        $codigo_filtro = mysqli_real_escape_string($db, $filtros['codigo_filtro'] ?? '');
        $nombre_propietario = mysqli_real_escape_string($db, $filtros['nombre_propietario'] ?? '');
        $tipo_movil_tablet = mysqli_real_escape_string($db, $filtros['tipo_movil_tablet'] ?? '');
        $barrio = mysqli_real_escape_string($db, $filtros['barrio'] ?? '');

        $query = "SELECT * FROM " . static::$tabla . " WHERE 1";

        if (!empty($ubicacion)) {
            $query .= " AND ubicacion LIKE '%$ubicacion%'";
        }

        if (!empty($barrio)) {
            $query .= " AND barrio LIKE '%$barrio%'";
        }

        if (!empty($tipo)) {
            $query .= " AND tipo = '$tipo'";
        }
        if (!empty($tipo_movil_tablet)) {
            $query .= " AND tipo = '$tipo_movil_tablet'";
        }

        if ($precioMin > 0) {
            $query .= " AND precio >= $precioMin";
        }

        if ($precioMax > 0) {
            $query .= " AND precio <= $precioMax";
        }
        if (property_exists(static::class, 'banos') && isset($filtros['banos']) && $filtros['banos'] !== '') {
        $banos = self::$db->escape_string($filtros['banos']);
        $query .= " AND banos = $banos";
        }
        if (property_exists(static::class, 'habitaciones') && isset($filtros['habitaciones']) && $filtros['habitaciones'] !== '') {
        $habitaciones = self::$db->escape_string($filtros['habitaciones']);
        $query .= " AND habitaciones = $habitaciones";
        }

        if ($area_minima > 0) {
            $query .= " AND area_total >= $area_minima";
        }

        if (!empty($modalidad_filtros)) {
            $query .= " AND modalidad = '$modalidad_filtros'";
        }
        if (!empty($tipo_unidad_filtros)) {
            $query .= " AND tipo_unidad = '$tipo_unidad_filtros'";
        }

        if (!empty($codigo_filtro)) {
            $query .= " AND codigo LIKE '$codigo_filtro'";
        }
        if (!empty($nombre_propietario)) {
            $nombre_propietario = strtolower($nombre_propietario);
            $query .= " AND LOWER(propietario) LIKE '%$nombre_propietario%'";
        }



        return self::consultarSQL($query);
    }

>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
    //FUNCTION ORDENAR PROPIEDADES

    public static function ordenarResultados($propiedades, $criterio) {
        switch ($criterio) {
            case 'mayor_precio':
                usort($propiedades, function ($a, $b) {
                    $precioA = (int) str_replace('.', '', $a->precio);
                    $precioB = (int) str_replace('.', '', $b->precio);
                    return $precioB <=> $precioA;
                });
                break;

            case 'menor_precio':
                usort($propiedades, function ($a, $b) {
                    $precioA = (int) str_replace('.', '', $a->precio);
                    $precioB = (int) str_replace('.', '', $b->precio);
                    return $precioA <=> $precioB;
                });
                break;
            case 'mayor_m2':
                usort($propiedades, fn($a, $b) => $b->area_total <=> $a->area_total);
                break;
            case 'menor_m2':
                usort($propiedades, fn($a, $b) => $a->area_total <=> $b->area_total);
                break;
        }

        return $propiedades;
    }


    //Lista todas las registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    //Obtiene determinado numero de registro
    public static function get($cantidad) {
        $cantidad = (int) $cantidad;
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY RAND() LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

        public static function getRecomendadas($ubicacion, $idExcluir, $cantidad) {
        $ubicacion = self::$db->escape_string($ubicacion);
        $idExcluir = (int) $idExcluir;
        $cantidad = (int) $cantidad;

        $query = "SELECT * FROM " . static::$tabla . " 
                WHERE ubicacion = '$ubicacion' 
                AND id != $idExcluir 
                ORDER BY RAND() 
                LIMIT $cantidad";

        return self::consultarSQL($query);
    }

        public static function where($columna, $valor) {
        $columna = self::$db->escape_string($columna);
        $valor = self::$db->escape_string($valor);

        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}'";
        return self::consultarSQL($query);
    }

        public static function todas() {
            $query = "SELECT * FROM " . static::$tabla;
            return self::consultarSQL($query);
        }

        public static function whereAll($columna, $valor) {
        $columna = self::$db->escape_string($columna);
        $valor = self::$db->escape_string($valor);

        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}'";
        return self::consultarSQL($query);
    }

    public function eliminarImg() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'}) . " LIMIT 1";
        return self::$db->query($query);
    }







    public static function contar() {
        $query = "SELECT COUNT(*) as total FROM " . static::$tabla;
        $resultado = self::$db->query($query);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }

    public static function getPaginadas($limite, $offset, $ordenar = null) {
        $limite = (int) $limite;
        $offset = (int) $offset;

        $orderSQL = "";

        switch ($ordenar) {
            case 'mayor_precio':
                $orderSQL = "ORDER BY precio DESC";
                break;
            case 'menor_precio':
                $orderSQL = "ORDER BY precio ASC";
                break;
            case 'recientes':
                $orderSQL = "ORDER BY id DESC";
                break;
            case 'mayor_m2':
                $orderSQL = "ORDER BY area_total DESC";
                break;
            case 'menor_m2':
                $orderSQL = "ORDER BY area_total ASC";
                break;
<<<<<<< HEAD
            default:
                // 🟢 Orden por defecto: más recientes primero
                $orderSQL = "ORDER BY id DESC";
                break;
=======
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
        }

        $query = "SELECT * FROM " . static::$tabla . " $orderSQL LIMIT $limite OFFSET $offset";
        return self::consultarSQL($query);
    }



<<<<<<< HEAD

=======
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
    //Busca un registro por su ID
    public static function find($id) {
    $id = (int) $id; // Forzamos entero
    $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";

    $resultado = self::consultarSQL($query);
    return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        //Consultar la base de datos
        $resultado = self::$db->query($query);
        //Interar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        //Liberar la memoria
        $resultado->free();

        //Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto; 
    }

    //Sincroniza el objeto en memoria con los cambios realizados por el usuario 
    public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value) ) {
                $this->$key = $value;
            }
        }
    }
}
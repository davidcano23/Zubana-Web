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
            header('location: /?Resultado=1');
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
            header('location: /?Resultado=2');
        }
}

//ELIMINAR UN REGISTRO
    public function eliminar() {
        //Eliminar propiedad 
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'}) . " LIMIT 1";

    $resultado = self::$db->query($query);

    if($resultado) {
        $this->borrarImagen();
            header('location: /?Resultado=3');
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

    //FUNCTION ORDENAR PROPIEDADES

    // En tu clase base ActiveRecord (o en un helper estático que puedas llamar)
        public static function ordenarResultados(array $propiedades, string $criterio): array
        {
            $parsePrecio = static function($p): int {
                if ($p === null) return 0;
                if (is_int($p)) return $p;
                // acepta "1.200.000" o "1200000"
                $num = preg_replace('/\D+/', '', (string)$p);
                return $num !== '' ? (int)$num : 0;
            };

            $getArea = static function($obj): int|float {
                // Si algún modelo usa otro nombre, mapéalo aquí.
                // Por ahora asumo 'area_total'; si no existe, 0.
                return isset($obj->area_total) && $obj->area_total !== null ? (float)$obj->area_total : 0;
            };

            $cmpIdDesc = static function($a, $b) {
                return ($b->id ?? 0) <=> ($a->id ?? 0);
            };

            switch ($criterio) {
                case 'mayor_precio':
                    usort($propiedades, function ($a, $b) use ($parsePrecio, $cmpIdDesc) {
                        $pa = $parsePrecio($a->precio ?? null);
                        $pb = $parsePrecio($b->precio ?? null);
                        $r = $pb <=> $pa;
                        return $r !== 0 ? $r : $cmpIdDesc($a, $b);
                    });
                    break;

                case 'menor_precio':
                    usort($propiedades, function ($a, $b) use ($parsePrecio, $cmpIdDesc) {
                        $pa = $parsePrecio($a->precio ?? null);
                        $pb = $parsePrecio($b->precio ?? null);
                        $r = $pa <=> $pb;
                        return $r !== 0 ? $r : $cmpIdDesc($a, $b);
                    });
                    break;

                case 'mayor_m2':
                    usort($propiedades, function ($a, $b) use ($getArea, $cmpIdDesc) {
                        $r = $getArea($b) <=> $getArea($a);   // DESC
                        return $r !== 0 ? $r : $cmpIdDesc($a, $b);
                    });
                    break;

                case 'mas_recientes':
                default:
                    usort($propiedades, $cmpIdDesc); // id DESC
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

            default:
                // 🟢 Orden por defecto: más recientes primero
                $orderSQL = "ORDER BY id DESC";
                break;
        }

        $query = "SELECT * FROM " . static::$tabla . " $orderSQL LIMIT $limite OFFSET $offset";
        return self::consultarSQL($query);
    }

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
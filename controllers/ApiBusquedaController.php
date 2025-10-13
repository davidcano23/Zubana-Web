<?php

namespace Controllers;

class ApiBusquedaController {
    public static function buscar() {
        header('Content-Type: application/json; charset=utf-8');

        $q = $_GET['busqueda'] ?? '';
        $q = trim($q);
        if ($q === '' || mb_strlen($q) < 2) { echo json_encode([]); return; }

        $link = conectarDB();
        if (!$link) { http_response_code(500); echo json_encode(['error'=>'DB connection']); return; }

        // Tablas a consultar (ajusta nombres exactos)
        $tablas = ['casa', 'apartamento', 'local', 'lotes'];

        $todos = [];
        foreach ($tablas as $t) {
            $todos = array_merge($todos, self::sugerenciasTabla($link, $t, $q));
        }

        // Deduplicar por texto (case-insensitive)
        $seen = [];
        $unicos = [];
        foreach ($todos as $r) {
            $key = mb_strtolower($r['texto']);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unicos[] = ['texto' => $r['texto'] /*, 'tipo' => $r['tipo'] */];
            }
        }

        // (Opcional) Limitar total global a 8–10
        $unicos = array_slice($unicos, 0, 10);

        echo json_encode($unicos);
    }



        private static function sugerenciasTabla($link, string $tabla, string $q): array {
            // Trae coincidencias de ubicacion y barrio y etiqueta la fuente
            $sql = "
                (SELECT DISTINCT ubicacion AS texto, 'ubicacion' AS fuente
                FROM {$tabla}
                WHERE ubicacion LIKE CONCAT('%', ?, '%')
                LIMIT 5)
                UNION ALL
                (SELECT DISTINCT barrio AS texto, 'barrio' AS fuente
                FROM {$tabla}
                WHERE barrio LIKE CONCAT('%', ?, '%')
                LIMIT 5)
            ";

            $stmt = mysqli_prepare($link, $sql);
            if (!$stmt) return [];

            mysqli_stmt_bind_param($stmt, 'ss', $q, $q);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            $out = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $texto = trim($row['texto'] ?? '');
                $fuente = $row['fuente'] ?? '';
                if ($texto !== '') {
                    $out[] = ['texto' => $texto, 'fuente' => $fuente, 'tipo' => $tabla];
                }
            }
            mysqli_stmt_close($stmt);
            return $out;
        }


    }

<?php

namespace Controllers;

use mysqli;

class ApiBusquedaController {

    public static function buscar() {
        header('Content-Type: application/json; charset=utf-8');

        $q = trim($_GET['busqueda'] ?? '');
        if ($q === '' || mb_strlen($q) < 2) {
            echo json_encode([]);
            exit;
        }

        $db = conectarDB();
        if (!$db instanceof mysqli) {
            http_response_code(500);
            echo json_encode([]);
            exit;
        }

        // Tablas a consultar
        $tablas = ['casa', 'apartamento', 'local', 'lotes'];

        $resultados = [];
        $seen = [];

        foreach ($tablas as $tabla) {
            $items = self::sugerenciasTabla($db, $tabla, $q);

            foreach ($items as $item) {
                $key = mb_strtolower($item['texto']);
                if (!isset($seen[$key])) {
                    $seen[$key] = true;
                    $resultados[] = ['texto' => $item['texto']];
                }
            }

            // Corte anticipado para rendimiento
            if (count($resultados) >= 10) {
                break;
            }
        }

        echo json_encode(array_slice($resultados, 0, 10));
        exit;
    }

    /**
     * Obtiene sugerencias de una tabla para varias columnas
     */
    private static function sugerenciasTabla(mysqli $db, string $tabla, string $q): array {

        $campos = ['ubicacion', 'barrio', 'corregimiento', 'palabra_clave'];
        $out = [];

        foreach ($campos as $campo) {

            $sql = "
                SELECT DISTINCT TRIM($campo)
                FROM {$tabla}
                WHERE $campo IS NOT NULL
                AND $campo <> ''
                AND $campo COLLATE utf8mb4_unicode_ci
                    LIKE CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci
                LIMIT 5
            ";

            $stmt = $db->prepare($sql);
            if (!$stmt) {
                continue;
            }

            $stmt->bind_param('s', $q);
            $stmt->execute();
            $stmt->bind_result($texto);

            while ($stmt->fetch()) {
                if ($texto !== '') {
                    $out[] = [
                        'texto' => $texto,
                        'campo' => $campo,
                        'tabla' => $tabla
                    ];
                }
            }

            $stmt->close();
        }

        return $out;
    }
}

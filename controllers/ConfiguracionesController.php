<?php

namespace Controllers;

use MVC\Router;

class ConfiguracionesController {

    private static function settingsPath(): string {
        return __DIR__ . '/../includes/config/site_settings.json';
    }

    private static function load(): array {
        $path = self::settingsPath();
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }

    private static function defaults(): array {
        return [
            'color_fondo'        => '#1C1C1E',
            'color_header'       => '#343434',
            'color_texto'        => '#F5F1EA',
            'color_acento'       => '#C1442E',
            'color_filtros'      => '#1C1C1E',
            'fuente_titulos'     => 'Playfair Display',
            'fuente_cuerpo'      => 'Lato',
            'fuente_titulos_url' => '',
            'fuente_cuerpo_url'  => '',
            'logo_principal'     => '',   // vacío = usar /img/logo_ZB.png
            'logo_secundario'    => '',   // vacío = usar /img/logo_header_horizontal.png
        ];
    }

    private static function predefinedTitulosFonts(): array {
        return [
            'Playfair Display', 'Lora', 'Merriweather', 'Cormorant Garamond',
            'Cinzel', 'Libre Baskerville', 'Josefin Sans', 'Raleway',
            'Montserrat', 'Bebas Neue', 'Abril Fatface', 'DM Serif Display',
        ];
    }

    private static function predefinedCuerpoFonts(): array {
        return [
            'Lato', 'Roboto', 'Open Sans', 'Raleway', 'Nunito',
            'Inter', 'Poppins', 'Source Sans 3', 'DM Sans', 'Montserrat',
            'Outfit', 'Figtree', 'Plus Jakarta Sans',
        ];
    }

    public static function index(Router $router): void {
        $auth = $_SESSION['login'] ?? null;
        if (!$auth) { header('Location: /'); exit; }

        $settings = array_merge(self::defaults(), self::load());
        $exito    = $_SESSION['config_exito'] ?? null;
        unset($_SESSION['config_exito']);

        $router->render('configuraciones/index', [
            'settings'       => $settings,
            'exito'          => $exito,
            'titulosOpciones'=> self::predefinedTitulosFonts(),
            'cuerpoOpciones' => self::predefinedCuerpoFonts(),
        ]);
    }

    public static function guardar(Router $router): void {
        $auth = $_SESSION['login'] ?? null;
        if (!$auth) { header('Location: /'); exit; }

        $existing = self::load();
        $defaults = self::defaults();
        $settings = [];

        // Colores
        foreach (['color_fondo', 'color_header', 'color_texto', 'color_acento', 'color_filtros'] as $key) {
            $val = trim($_POST[$key] ?? '');
            $settings[$key] = preg_match('/^#[0-9A-Fa-f]{6}$/', $val) ? $val : $defaults[$key];
        }

        // Fuentes: si viene URL personalizada válida, aceptamos cualquier nombre
        foreach (['titulos', 'cuerpo'] as $slot) {
            $nameKey = "fuente_{$slot}";
            $urlKey  = "fuente_{$slot}_url";

            $url  = trim($_POST[$urlKey] ?? '');
            $name = trim($_POST[$nameKey] ?? '');

            $validUrl = str_starts_with($url, 'https://fonts.googleapis.com/') && $url !== '';
            $settings[$urlKey] = $validUrl ? $url : '';

            if ($validUrl && $name !== '') {
                // Fuente personalizada: aceptamos el nombre tal cual
                $settings[$nameKey] = $name;
            } else {
                $predefined = $slot === 'titulos'
                    ? self::predefinedTitulosFonts()
                    : self::predefinedCuerpoFonts();
                $settings[$nameKey] = in_array($name, $predefined, true)
                    ? $name
                    : $defaults[$nameKey];
            }
        }

        // Logos
        foreach (['logo_principal', 'logo_secundario'] as $logoKey) {
            if (!empty($_FILES[$logoKey]['name']) && $_FILES[$logoKey]['error'] === UPLOAD_ERR_OK) {
                $tmp  = $_FILES[$logoKey]['tmp_name'];
                $orig = $_FILES[$logoKey]['name'];
                $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

                if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'], true)) {
                    $newName = $logoKey . '_custom.' . $ext;
                    $dest    = __DIR__ . '/../public/img/' . $newName;
                    if (move_uploaded_file($tmp, $dest)) {
                        $settings[$logoKey] = '/img/' . $newName;
                    } else {
                        $settings[$logoKey] = $existing[$logoKey] ?? '';
                    }
                } else {
                    $settings[$logoKey] = $existing[$logoKey] ?? '';
                }
            } else {
                // Sin nuevo archivo → conservar el guardado anteriormente
                $settings[$logoKey] = $existing[$logoKey] ?? '';
            }
        }

        file_put_contents(self::settingsPath(), json_encode($settings, JSON_PRETTY_PRINT));

        $_SESSION['config_exito'] = 'Configuración guardada correctamente.';
        header('Location: /configuraciones');
        exit;
    }

    public static function resetear(): void {
        $auth = $_SESSION['login'] ?? null;
        if (!$auth) { header('Location: /'); exit; }

        // Eliminar logos personalizados si existen
        foreach (['logo_principal', 'logo_secundario'] as $key) {
            foreach (['png','jpg','jpeg','webp','svg'] as $ext) {
                $file = __DIR__ . "/../public/img/{$key}_custom.{$ext}";
                if (file_exists($file)) @unlink($file);
            }
        }

        $path = self::settingsPath();
        if (file_exists($path)) unlink($path);

        $_SESSION['config_exito'] = 'Configuración restaurada a los valores predeterminados.';
        header('Location: /configuraciones');
        exit;
    }
}

<?php

namespace Controllers;

use MVC\Router;
use Model\Cliente;
use Model\CrmActividad;

class WhatsAppController {

    // ── GET /crm/whatsapp ─────────────────────────────────────
    // Vista de configuración (admin)
    public static function configuracion(Router $router): void {
        if (empty($_SESSION['login'])) { header('Location: /'); exit; }
        $router->render('crm/whatsapp', []);
    }

    // ── GET /webhook/whatsapp ─────────────────────────────────
    // Meta llama a este endpoint para verificar el webhook
    public static function verificar(): void {
        require_once __DIR__ . '/../includes/config/whatsapp.php';

        $mode      = $_GET['hub_mode']         ?? '';
        $token     = $_GET['hub_verify_token'] ?? '';
        $challenge = $_GET['hub_challenge']    ?? '';

        if ($mode === 'subscribe' && $token === WA_VERIFY_TOKEN) {
            http_response_code(200);
            echo $challenge;
        } else {
            http_response_code(403);
            echo 'Token inválido';
        }
        exit;
    }

    // ── POST /webhook/whatsapp ────────────────────────────────
    // Meta envía aquí cada mensaje que llega al número de WA Business
    public static function recibir(): void {
        require_once __DIR__ . '/../includes/config/whatsapp.php';

        $rawPayload = file_get_contents('php://input');

        // Verificar firma de seguridad de Meta
        $firma    = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
        $esperada = 'sha256=' . hash_hmac('sha256', $rawPayload, WA_APP_SECRET);

        if (WA_APP_SECRET !== 'TU_APP_SECRET_AQUI' && !hash_equals($esperada, $firma)) {
            http_response_code(403);
            exit;
        }

        $data = json_decode($rawPayload, true);

        if (($data['object'] ?? '') !== 'whatsapp_business_account') {
            http_response_code(200);
            exit;
        }

        foreach ($data['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? '') !== 'messages') continue;

                $value    = $change['value']    ?? [];
                $messages = $value['messages']  ?? [];
                $contacts = $value['contacts']  ?? [];

                // Indexar contactos por wa_id para acceso rápido
                $contactMap = [];
                foreach ($contacts as $c) {
                    $contactMap[$c['wa_id']] = $c['profile']['name'] ?? '';
                }

                foreach ($messages as $msg) {
                    $tipo  = $msg['type'] ?? '';
                    $phone = $msg['from'] ?? '';

                    if (!$phone) continue;

                    $waName = $contactMap[$phone] ?? '';
                    $texto  = self::extraerTexto($msg, $tipo);

                    if ($texto === '') continue;

                    self::procesarMensaje($phone, $waName, $texto, $tipo);
                }
            }
        }

        http_response_code(200);
        echo json_encode(['status' => 'ok']);
        exit;
    }

    // ─────────────────────────────────────────────────────────

    private static function extraerTexto(array $msg, string $tipo): string {
        return match ($tipo) {
            'text'     => $msg['text']['body']               ?? '',
            'image'    => '[Imagen] ' . ($msg['image']['caption']    ?? ''),
            'video'    => '[Video] '  . ($msg['video']['caption']    ?? ''),
            'document' => '[Documento: ' . ($msg['document']['filename'] ?? '') . ']',
            'audio'    => '[Nota de voz]',
            'location' => '[Ubicación] lat: ' . ($msg['location']['latitude'] ?? '')
                        . ', lng: ' . ($msg['location']['longitude'] ?? ''),
            default    => '',
        };
    }

    private static function procesarMensaje(
        string $phone,
        string $waName,
        string $texto,
        string $tipo
    ): void {
        $telefonoNorm = self::normalizarTelefono($phone);

        // Buscar cliente existente por teléfono (intenta varias variantes)
        $cliente = self::buscarPorTelefono($telefonoNorm, $phone);

        if (!$cliente) {
            // Crear nuevo cliente automáticamente
            $partes   = self::partirNombre($waName ?: 'Lead WhatsApp');
            $nuevo    = new Cliente();
            $nuevo->nombre   = $partes[0];
            $nuevo->apellido = $partes[1];
            $nuevo->telefono = $telefonoNorm;
            $nuevo->fuente   = 'WhatsApp';
            $nuevo->estado   = 'nuevo';
            $nuevo->notas    = 'Creado automáticamente desde WhatsApp';

            if (!$nuevo->crearCliente()) return;
            $cliente = $nuevo;
        }

        if (!$cliente->id) return;

        // Registrar el mensaje como actividad
        $act = new CrmActividad();
        $act->cliente_id  = (int) $cliente->id;
        $act->tipo        = 'whatsapp';
        $act->descripcion = $texto;
        $act->crearActividad();
    }

    private static function buscarPorTelefono(string $telefonoNorm, string $phoneRaw): ?object {
        // Intento 1: número normalizado (ej: "3001234567")
        $res = Cliente::where('telefono', $telefonoNorm);
        if (!empty($res)) return $res[0];

        // Intento 2: número completo con código de país (ej: "573001234567")
        $res = Cliente::where('telefono', $phoneRaw);
        if (!empty($res)) return $res[0];

        // Intento 3: con + delante
        $res = Cliente::where('telefono', '+' . $phoneRaw);
        if (!empty($res)) return $res[0];

        return null;
    }

    private static function normalizarTelefono(string $phone): string {
        // WhatsApp envía el número en formato internacional sin '+': ej. "573001234567"
        $codigoPais = WA_CODIGO_PAIS; // "57" para Colombia
        $len = strlen($codigoPais);

        if (str_starts_with($phone, $codigoPais) && strlen($phone) === $len + 10) {
            return substr($phone, $len); // "3001234567"
        }

        return $phone;
    }

    private static function partirNombre(string $nombreCompleto): array {
        $partes   = explode(' ', trim($nombreCompleto), 2);
        $nombre   = $partes[0] ?? 'Lead';
        $apellido = $partes[1] ?? '';
        return [$nombre, $apellido];
    }
}

<?php
// ═══════════════════════════════════════════════════════════
//  Configuración de WhatsApp Business Cloud API
//  Obtener los valores desde: Meta for Developers →
//  Tu App → WhatsApp → Configuración de API
// ═══════════════════════════════════════════════════════════

// Token que TÚ inventas (debe coincidir con el que pones en Meta Developer Console)
define('WA_VERIFY_TOKEN',    'zubana_wh_token_2024');

// Access Token de Meta (desde Meta Developer Console → tu App → WhatsApp → API Setup)
define('WA_ACCESS_TOKEN',    'TU_ACCESS_TOKEN_AQUI');

// App Secret (desde Meta Developer Console → tu App → Configuración → Básica)
define('WA_APP_SECRET',      'TU_APP_SECRET_AQUI');

// Phone Number ID (desde Meta Developer Console → tu App → WhatsApp → API Setup)
define('WA_PHONE_NUMBER_ID', 'TU_PHONE_NUMBER_ID_AQUI');

// Código de país por defecto para normalizar números (57 = Colombia)
define('WA_CODIGO_PAIS',     '57');

<?php
require_once __DIR__ . '/../../includes/config/whatsapp.php';
$configurado = WA_ACCESS_TOKEN !== 'TU_ACCESS_TOKEN_AQUI'
            && WA_APP_SECRET   !== 'TU_APP_SECRET_AQUI'
            && WA_PHONE_NUMBER_ID !== 'TU_PHONE_NUMBER_ID_AQUI';

$webhookUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/webhook/whatsapp';
?>

<main class="crm-page">
<div class="crm-wrap crm-wrap--form">

    <div class="crm-breadcrumb">
        <a href="/crm">CRM</a>
        <span>›</span>
        <span>Integración WhatsApp</span>
    </div>

    <h1>WhatsApp Business</h1>
    <p class="crm-subtitle">Los mensajes que lleguen a tu número de WhatsApp Business se guardarán automáticamente en el CRM.</p>

    <!-- Estado -->
    <div class="wa-status <?= $configurado ? 'wa-status--ok' : 'wa-status--pending' ?>">
        <span class="wa-status__dot"></span>
        <?= $configurado ? 'Integración configurada' : 'Pendiente de configuración' ?>
    </div>

    <!-- URL del webhook -->
    <section class="crm-form-section" style="margin-top:2rem">
        <legend>URL del Webhook</legend>
        <p style="color:var(--color-texto,#F5F1EA);font-family:sans-serif;font-size:1.4rem;margin:1.2rem 0 .6rem;opacity:.7">
            Copia esta URL y pégala en Meta Developer Console → Tu App → WhatsApp → Configuración
        </p>
        <div class="wa-copy-field">
            <code id="webhookUrl"><?= htmlspecialchars($webhookUrl, ENT_QUOTES) ?></code>
            <button type="button" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($webhookUrl, ENT_QUOTES) ?>');this.textContent='Copiado ✓'" class="wa-copy-btn">Copiar</button>
        </div>

        <p style="color:var(--color-texto,#F5F1EA);font-family:sans-serif;font-size:1.4rem;margin:1.4rem 0 .6rem;opacity:.7">
            Token de verificación (usar este mismo en Meta Developer Console)
        </p>
        <div class="wa-copy-field">
            <code><?= htmlspecialchars(WA_VERIFY_TOKEN, ENT_QUOTES) ?></code>
            <button type="button" onclick="navigator.clipboard.writeText('<?= htmlspecialchars(WA_VERIFY_TOKEN, ENT_QUOTES) ?>');this.textContent='Copiado ✓'" class="wa-copy-btn">Copiar</button>
        </div>
    </section>

    <!-- Instrucciones -->
    <section class="crm-form-section" style="margin-top:1.5rem">
        <legend>Pasos de configuración</legend>
        <ol class="wa-steps">
            <li>
                <strong>Crea una App en Meta for Developers</strong>
                <span>Ve a <em>developers.facebook.com</em> → Mis Apps → Crear App → Tipo: Empresa</span>
            </li>
            <li>
                <strong>Agrega WhatsApp a la App</strong>
                <span>Dentro de la App → Agregar producto → WhatsApp</span>
            </li>
            <li>
                <strong>Configura el Webhook</strong>
                <span>WhatsApp → Configuración → Webhooks → Editar<br>
                URL: <code class="wa-inline-code"><?= htmlspecialchars($webhookUrl, ENT_QUOTES) ?></code><br>
                Token de verificación: <code class="wa-inline-code"><?= htmlspecialchars(WA_VERIFY_TOKEN, ENT_QUOTES) ?></code><br>
                Suscribir al campo: <code class="wa-inline-code">messages</code></span>
            </li>
            <li>
                <strong>Copia los tokens en el archivo de configuración</strong>
                <span>Abre <code class="wa-inline-code">includes/config/whatsapp.php</code> y reemplaza:<br>
                • <code class="wa-inline-code">WA_ACCESS_TOKEN</code> → Token de acceso permanente<br>
                • <code class="wa-inline-code">WA_APP_SECRET</code> → App Secret (Configuración Básica)<br>
                • <code class="wa-inline-code">WA_PHONE_NUMBER_ID</code> → ID del número de teléfono</span>
            </li>
            <li>
                <strong>Verifica que el webhook esté activo</strong>
                <span>Meta enviará una solicitud de verificación a la URL. Si el servidor está en producción con HTTPS, se verificará automáticamente.</span>
            </li>
        </ol>
    </section>

    <!-- Config actual -->
    <section class="crm-form-section" style="margin-top:1.5rem">
        <legend>Estado actual de la configuración</legend>
        <ul class="wa-check-list">
            <li class="<?= WA_VERIFY_TOKEN   ? 'ok' : 'err' ?>">Token de verificación</li>
            <li class="<?= WA_ACCESS_TOKEN   !== 'TU_ACCESS_TOKEN_AQUI'   ? 'ok' : 'err' ?>">Access Token de Meta</li>
            <li class="<?= WA_APP_SECRET     !== 'TU_APP_SECRET_AQUI'     ? 'ok' : 'err' ?>">App Secret</li>
            <li class="<?= WA_PHONE_NUMBER_ID !== 'TU_PHONE_NUMBER_ID_AQUI' ? 'ok' : 'err' ?>">Phone Number ID</li>
        </ul>
    </section>

    <div style="margin-top:2rem">
        <a href="/crm" class="crm-btn crm-btn--secondary">← Volver al CRM</a>
    </div>
</div>
</main>

<style>
.wa-status {
    display: inline-flex;
    align-items: center;
    gap: .7rem;
    padding: .75rem 1.4rem;
    border-radius: 2rem;
    font-size: 1.4rem;
    font-family: sans-serif;
    margin: 1.5rem 0;

    &__dot {
        width: .85rem;
        height: .85rem;
        border-radius: 50%;
        flex-shrink: 0;
    }

    &--ok {
        background: color-mix(in srgb, #10B981 14%, transparent);
        color: #6ee7b7;
        border: 1px solid color-mix(in srgb, #10B981 30%, transparent);
        .wa-status__dot { background: #10B981; }
    }

    &--pending {
        background: color-mix(in srgb, #F59E0B 14%, transparent);
        color: #fcd34d;
        border: 1px solid color-mix(in srgb, #F59E0B 30%, transparent);
        .wa-status__dot { background: #F59E0B; }
    }
}

.wa-copy-field {
    display: flex;
    align-items: center;
    gap: .9rem;
    background: color-mix(in srgb, #0A0A0A 55%, transparent);
    border: 1px solid color-mix(in srgb, #F5F1EA 12%, transparent);
    border-radius: .9rem;
    padding: .9rem 1.2rem;

    code {
        flex: 1;
        font-size: 1.35rem;
        color: #A4C8D8;
        word-break: break-all;
        font-family: monospace;
    }
}

.wa-copy-btn {
    padding: .5rem 1.1rem;
    background: color-mix(in srgb, #F5F1EA 10%, transparent);
    border: 1px solid color-mix(in srgb, #F5F1EA 18%, transparent);
    border-radius: .6rem;
    color: #F5F1EA;
    font-size: 1.25rem;
    cursor: pointer;
    white-space: nowrap;
    transition: background .15s ease;
    &:hover { background: color-mix(in srgb, #F5F1EA 18%, transparent); }
}

.wa-steps {
    margin: 1.4rem 0 0;
    padding-left: 1.8rem;
    display: flex;
    flex-direction: column;
    gap: 1.4rem;

    li {
        color: color-mix(in srgb, #F5F1EA 70%, transparent);
        font-size: 1.35rem;
        font-family: sans-serif;
        line-height: 1.7;

        strong {
            display: block;
            color: #F5F1EA;
            font-weight: 600;
            margin-bottom: .3rem;
        }

        span { display: block; }
    }
}

.wa-inline-code {
    background: color-mix(in srgb, #0A0A0A 60%, transparent);
    border: 1px solid color-mix(in srgb, #F5F1EA 12%, transparent);
    border-radius: .4rem;
    padding: .1rem .5rem;
    font-size: 1.2rem;
    font-family: monospace;
    color: #A4C8D8;
}

.wa-check-list {
    list-style: none;
    padding: 0;
    margin: 1.4rem 0 0;
    display: flex;
    flex-direction: column;
    gap: .7rem;

    li {
        display: flex;
        align-items: center;
        gap: .8rem;
        font-size: 1.35rem;
        font-family: sans-serif;
        padding: .7rem 1rem;
        border-radius: .7rem;

        &::before {
            content: '';
            width: .8rem;
            height: .8rem;
            border-radius: 50%;
            flex-shrink: 0;
        }

        &.ok {
            color: #6ee7b7;
            background: color-mix(in srgb, #10B981 10%, transparent);
            &::before { background: #10B981; }
        }

        &.err {
            color: color-mix(in srgb, #F5F1EA 45%, transparent);
            background: color-mix(in srgb, #F5F1EA 4%, transparent);
            &::before { background: #6B7280; }
        }
    }
}
</style>

<?php
$esNuevo  = $esNuevo  ?? true;
$errores  = $errores  ?? [];
$etapas   = $etapas   ?? [];
$fuentes  = $fuentes  ?? [];
$tiposB   = $tiposB   ?? [];
$tiposP   = $tiposP   ?? [];

// Si viene ?estado=X pre-seleccionar
if ($esNuevo && isset($_GET['estado']) && array_key_exists($_GET['estado'], $etapas)) {
    $cliente->estado = $_GET['estado'];
}

$accion = $esNuevo ? '/crm/crear' : '/crm/editar';
$titulo = $esNuevo ? 'Nuevo cliente' : 'Editar cliente';
?>

<main class="crm-page">
<div class="crm-wrap crm-wrap--form">

    <div class="crm-breadcrumb">
        <a href="/crm">CRM</a>
        <span>›</span>
        <?php if (!$esNuevo): ?>
        <a href="/crm/cliente?id=<?= $cliente->id ?>">
            <?= htmlspecialchars($cliente->nombreCompleto(), ENT_QUOTES) ?>
        </a>
        <span>›</span>
        <?php endif; ?>
        <span><?= $titulo ?></span>
    </div>

    <h1><?= $titulo ?></h1>

    <?php if (!empty($errores)): ?>
    <div class="crm-alert crm-alert--err">
        <ul>
            <?php foreach ($errores as $e): ?>
            <li><?= htmlspecialchars($e, ENT_QUOTES) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= $accion ?>" class="crm-form">
        <?php if (!$esNuevo): ?>
        <input type="hidden" name="id" value="<?= $cliente->id ?>">
        <?php endif; ?>

        <!-- Contacto -->
        <fieldset class="crm-form-section">
            <legend>Información de contacto</legend>
            <div class="crm-form-grid crm-form-grid--2">
                <div class="crm-form-field">
                    <label for="nombre">Nombre <span class="crm-required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required
                           value="<?= htmlspecialchars($cliente->nombre, ENT_QUOTES) ?>"
                           placeholder="Ej: Juan">
                </div>
                <div class="crm-form-field">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido"
                           value="<?= htmlspecialchars($cliente->apellido, ENT_QUOTES) ?>"
                           placeholder="Ej: García">
                </div>
                <div class="crm-form-field">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono"
                           value="<?= htmlspecialchars($cliente->telefono, ENT_QUOTES) ?>"
                           placeholder="Ej: 300 123 4567">
                </div>
                <div class="crm-form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($cliente->email, ENT_QUOTES) ?>"
                           placeholder="Ej: juan@correo.com">
                </div>
                <div class="crm-form-field">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad"
                           value="<?= htmlspecialchars($cliente->ciudad, ENT_QUOTES) ?>"
                           placeholder="Ej: Medellín">
                </div>
            </div>
        </fieldset>

        <!-- Intereses -->
        <fieldset class="crm-form-section">
            <legend>Intereses en propiedad</legend>
            <div class="crm-form-grid crm-form-grid--2">
                <div class="crm-form-field">
                    <label for="tipo_busqueda">Tipo de búsqueda</label>
                    <select id="tipo_busqueda" name="tipo_busqueda" class="crm-select">
                        <?php foreach ($tiposB as $t): ?>
                        <option value="<?= $t ?>" <?= $cliente->tipo_busqueda === $t ? 'selected' : '' ?>>
                            <?= $t ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="crm-form-field">
                    <label for="tipo_propiedad">Tipo de propiedad</label>
                    <select id="tipo_propiedad" name="tipo_propiedad" class="crm-select">
                        <option value="">— Cualquiera —</option>
                        <?php foreach ($tiposP as $t): ?>
                        <option value="<?= $t ?>" <?= $cliente->tipo_propiedad === $t ? 'selected' : '' ?>>
                            <?= $t ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="crm-form-field">
                    <label for="presupuesto_min">Presupuesto mínimo</label>
                    <input type="text" id="presupuesto_min" name="presupuesto_min"
                           value="<?= $cliente->presupuesto_min > 0 ? number_format((int)$cliente->presupuesto_min, 0, ',', '.') : '' ?>"
                           placeholder="Ej: 200.000.000"
                           inputmode="numeric">
                </div>
                <div class="crm-form-field">
                    <label for="presupuesto_max">Presupuesto máximo</label>
                    <input type="text" id="presupuesto_max" name="presupuesto_max"
                           value="<?= $cliente->presupuesto_max > 0 ? number_format((int)$cliente->presupuesto_max, 0, ',', '.') : '' ?>"
                           placeholder="Ej: 500.000.000"
                           inputmode="numeric">
                </div>
            </div>
        </fieldset>

        <!-- CRM -->
        <fieldset class="crm-form-section">
            <legend>CRM</legend>
            <div class="crm-form-grid crm-form-grid--2">
                <div class="crm-form-field">
                    <label for="fuente">Origen / Fuente</label>
                    <select id="fuente" name="fuente" class="crm-select">
                        <?php foreach ($fuentes as $f): ?>
                        <option value="<?= $f ?>" <?= $cliente->fuente === $f ? 'selected' : '' ?>>
                            <?= $f ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="crm-form-field">
                    <label for="estado">Etapa en el embudo</label>
                    <select id="estado" name="estado" class="crm-select">
                        <?php foreach ($etapas as $k => $e): ?>
                        <option value="<?= $k ?>" <?= $cliente->estado === $k ? 'selected' : '' ?>>
                            <?= $e['label'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </fieldset>

        <!-- Notas -->
        <fieldset class="crm-form-section">
            <legend>Notas</legend>
            <div class="crm-form-field">
                <textarea id="notas" name="notas" class="crm-textarea" rows="4"
                          placeholder="Comentarios adicionales sobre el cliente…"><?= htmlspecialchars($cliente->notas ?? '', ENT_QUOTES) ?></textarea>
            </div>
        </fieldset>

        <!-- Acciones -->
        <div class="crm-form-actions">
            <button type="submit" class="crm-btn crm-btn--primary">
                <?= $esNuevo ? 'Crear cliente' : 'Guardar cambios' ?>
            </button>
            <a href="<?= $esNuevo ? '/crm' : "/crm/cliente?id={$cliente->id}" ?>"
               class="crm-btn crm-btn--secondary">Cancelar</a>
        </div>
    </form>
</div>
</main>

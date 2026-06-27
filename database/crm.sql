-- ═══════════════════════════════════════════════
--  CRM — Gestión de Clientes
--  Ejecutar una sola vez en tu base de datos MySQL
-- ═══════════════════════════════════════════════

CREATE TABLE IF NOT EXISTS `clientes` (
    `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nombre`          VARCHAR(100)     NOT NULL,
    `apellido`        VARCHAR(100)     NOT NULL DEFAULT '',
    `telefono`        VARCHAR(25)      NOT NULL DEFAULT '',
    `email`           VARCHAR(150)     NOT NULL DEFAULT '',
    `ciudad`          VARCHAR(100)     NOT NULL DEFAULT '',
    `presupuesto_min` BIGINT UNSIGNED  NOT NULL DEFAULT 0,
    `presupuesto_max` BIGINT UNSIGNED  NOT NULL DEFAULT 0,
    `tipo_busqueda`   ENUM('Compra','Arriendo','Ambos') NOT NULL DEFAULT 'Compra',
    `tipo_propiedad`  VARCHAR(100)     NOT NULL DEFAULT '',
    `fuente`          ENUM('Web','WhatsApp','Instagram','Facebook','TikTok','Referido','Llamada','Otro') NOT NULL DEFAULT 'Otro',
    `estado`          ENUM('nuevo','contactado','interesado','negociacion','cerrado','perdido') NOT NULL DEFAULT 'nuevo',
    `notas`           TEXT,
    `created_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_actividades` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `cliente_id`  INT UNSIGNED NOT NULL,
    `tipo`        ENUM('nota','llamada','whatsapp','visita','email','otro') NOT NULL DEFAULT 'nota',
    `descripcion` TEXT         NOT NULL,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_actividad_cliente`
        FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

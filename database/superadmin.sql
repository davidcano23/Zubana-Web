-- =====================================================
-- Fase 7 · Tarea 1: Autenticación superadmin separada
-- Tabla GLOBAL (sin tenant_id): son NUESTROS usuarios,
-- no pertenecen a ninguna inmobiliaria.
-- =====================================================

CREATE TABLE IF NOT EXISTS `superadmins` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nombre`     VARCHAR(100)     NOT NULL DEFAULT '',
    `email`      VARCHAR(255)     NOT NULL,
    `password`   VARCHAR(255)     NOT NULL,          -- hash de password_hash()
    `activo`     TINYINT(1)       NOT NULL DEFAULT 1, -- permite desactivar sin borrar
    `created_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_superadmins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear el primer superadmin:
-- 1) Genera el hash en PHP:  php -r "echo password_hash('TU_PASSWORD', PASSWORD_DEFAULT);"
-- 2) Reemplaza abajo y ejecuta:
-- INSERT INTO superadmins (nombre, email, password)
-- VALUES ('David', 'davidcanomarin23@gmail.com', '$2y$10$REEMPLAZA_CON_EL_HASH');

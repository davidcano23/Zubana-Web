-- ═══════════════════════════════════════════════
--  Migración: columnas video_url, jacuzzi, turco
--  Ejecutar una sola vez en tu base de datos MySQL
--  Compatible con MySQL 8.0+ y MariaDB 10.0+
-- ═══════════════════════════════════════════════

ALTER TABLE `casa`
    ADD COLUMN IF NOT EXISTS `jacuzzi`   VARCHAR(5)   NOT NULL DEFAULT 'No',
    ADD COLUMN IF NOT EXISTS `turco`     VARCHAR(5)   NOT NULL DEFAULT 'No',
    ADD COLUMN IF NOT EXISTS `video_url` VARCHAR(500) NOT NULL DEFAULT 'N/A';

ALTER TABLE `apartamento`
    ADD COLUMN IF NOT EXISTS `jacuzzi`   VARCHAR(5)   NOT NULL DEFAULT 'No',
    ADD COLUMN IF NOT EXISTS `turco`     VARCHAR(5)   NOT NULL DEFAULT 'No',
    ADD COLUMN IF NOT EXISTS `video_url` VARCHAR(500) NOT NULL DEFAULT 'N/A';

ALTER TABLE `lotes`
    ADD COLUMN IF NOT EXISTS `video_url` VARCHAR(500) NOT NULL DEFAULT 'N/A';

ALTER TABLE `local`
    ADD COLUMN IF NOT EXISTS `video_url` VARCHAR(500) NOT NULL DEFAULT 'N/A';

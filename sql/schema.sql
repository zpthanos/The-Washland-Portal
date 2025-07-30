BEGIN;

-- Use strict mode for data integrity
SET
    SESSION sql_mode = 'STRICT_ALL_TABLES';

-- 1. Create database if missing
CREATE DATABASE IF NOT EXISTS `washland` CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_unicode_ci;

USE `washland`;

-- 2. Create cards table with industry best practices
CREATE TABLE
    IF NOT EXISTS `cards` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `card_code` VARCHAR(255) NOT NULL,
        `fullname` VARCHAR(255) NOT NULL,
        `description` TEXT,
        `purchase_date` DATE NOT NULL,
        `type` ENUM ('Συνεργάτης', 'Πελάτης') NOT NULL,
        `price` DECIMAL(10, 2) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uk_cards_card_code` (`card_code`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

COMMIT;
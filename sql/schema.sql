-- Лабораторная работа №3. Таблица пользователей
-- Ласточкин О.О.
SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS php_lastochkin
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_lastochkin;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id            INT          NOT NULL AUTO_INCREMENT,
    login         VARCHAR(50)  NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    bg_color      CHAR(7)      NOT NULL DEFAULT '#ffffff',
    text_color    CHAR(7)      NOT NULL DEFAULT '#000000',
    remember_hash CHAR(64)     NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_login (login),
    UNIQUE KEY uq_users_remember (remember_hash)
) ENGINE = InnoDB;

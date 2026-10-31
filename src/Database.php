<?php

namespace App;

use PDO;

/**
 * Подключение к базе данных через PDO (одно соединение на запрос).
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $cfg = require __DIR__ . '/../config.php';
            $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};"
                 . "dbname={$cfg['dbname']};charset={$cfg['charset']}";
            self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}

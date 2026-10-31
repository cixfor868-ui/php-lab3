<?php
// Параметры подключения к базе данных.
// В Open Server Panel 6 хост MySQL – это имя модуля (MySQL-8.0),
// пользователь root, пароль по умолчанию пустой.
$host = getenv('DB_HOST') ?: 'MySQL-8.0';
if (!filter_var($host, FILTER_VALIDATE_IP) && gethostbyname($host) === $host) {
    $host = '127.0.1.30'; // IP модуля MySQL-8.0 по умолчанию
}

return [
    'host'    => $host,
    'port'    => 3306,
    'dbname'  => 'php_lastochkin',
    'user'    => getenv('DB_USER') ?: 'root',
    'pass'    => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];

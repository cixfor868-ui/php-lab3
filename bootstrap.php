<?php
// Общая инициализация: автозагрузка Composer и объект авторизации
require_once __DIR__ . '/vendor/autoload.php';

use App\Auth;
use App\Database;
use App\UserRepository;

date_default_timezone_set('Europe/Moscow');

$auth = new Auth(new UserRepository(Database::get()));

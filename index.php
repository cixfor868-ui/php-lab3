<?php

require 'vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

use App\View;

// Без входа – на страницу авторизации; при наличии cookie remember
// пользователь будет авторизован автоматически
$user = $auth->user();
if ($user === null) {
    View::redirect('login.php');
}

View::header('Главная');
?>
<h1>Добро пожаловать, <?= View::e($user['login']) ?>!</h1>
<p>Вы авторизованы. Цвет фона: <b><?= View::e($user['bg_color']) ?></b>,
   цвет шрифта: <b><?= View::e($user['text_color']) ?></b>.</p>
<p>Cookie, полученные браузером: <code id="cookies"></code></p>
<script>document.getElementById('cookies').textContent = document.cookie;</script>
<p><a href="settings.php">Настройки</a> | <a href="logout.php">Выйти</a></p>
<?php View::footer();

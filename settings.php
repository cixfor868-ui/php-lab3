<?php
require_once __DIR__ . '/bootstrap.php';

use App\View;

$user = $auth->user();
if ($user === null) {
    View::redirect('login.php');
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->saveSettings($user, $_POST['bg_color'] ?? '', $_POST['text_color'] ?? '');
    $user  = $auth->user(); // перечитываем из БД
    $saved = true;
}

View::header('Настройки');
?>
<h1>Настройки</h1>
<?php if ($saved): ?><p class="ok">Настройки сохранены</p><?php endif; ?>
<form method="post">
    <label>Цвет фона <input type="color" name="bg_color" value="<?= View::e($user['bg_color']) ?>"></label>
    <label>Цвет шрифта <input type="color" name="text_color" value="<?= View::e($user['text_color']) ?>"></label>
    <button type="submit">Сохранить</button>
</form>
<p><a href="index.php">На главную</a> | <a href="logout.php">Выйти</a></p>
<?php View::footer();

<?php
require_once __DIR__ . '/bootstrap.php';

use App\View;

if ($auth->user() !== null) {
    View::redirect('index.php');
}

$error = null;
$login = '';
$bg = '#ffffff';
$text = '#000000';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $bg    = $_POST['bg_color'] ?? $bg;
    $text  = $_POST['text_color'] ?? $text;
    $error = $auth->register($login, $_POST['password'] ?? '', $bg, $text);
    if ($error === null) {
        View::redirect('index.php');
    }
}

View::header('Регистрация');
?>
<h1>Регистрация</h1>
<?php if ($error): ?><p class="error"><?= View::e($error) ?></p><?php endif; ?>
<form method="post">
    <label>Логин <input type="text" name="login" value="<?= View::e($login) ?>" required></label>
    <label>Пароль <input type="password" name="password" required></label>
    <label>Цвет фона <input type="color" name="bg_color" value="<?= View::e($bg) ?>"></label>
    <label>Цвет шрифта <input type="color" name="text_color" value="<?= View::e($text) ?>"></label>
    <button type="submit">Зарегистрироваться</button>
</form>
<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
<?php View::footer();

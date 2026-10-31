<?php
require_once __DIR__ . '/bootstrap.php';

use App\View;

// если сессия продолжается (или сработал автовход по cookie) – сразу на главную
if ($auth->user() !== null) {
    View::redirect('index.php');
}

$error = null;
$login = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    if ($auth->login($login, $_POST['password'] ?? '')) {
        View::redirect('index.php');
    }
    $error = 'Неверный логин или пароль';
}

View::header('Вход');
?>
<h1>Авторизация</h1>
<?php if ($error): ?><p class="error"><?= View::e($error) ?></p><?php endif; ?>
<form method="post">
    <label>Логин <input type="text" name="login" value="<?= View::e($login) ?>" required></label>
    <label>Пароль <input type="password" name="password" required></label>
    <button type="submit">Войти</button>
</form>
<p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
<?php View::footer();

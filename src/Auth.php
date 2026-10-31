<?php

namespace App;

/**
 * Регистрация, вход, выход и автоматическая авторизация по cookie.
 */
class Auth
{
    public const COOKIE_REMEMBER = 'remember';
    public const COOKIE_BG       = 'bg_color';
    public const COOKIE_TEXT     = 'text_color';
    private const LIFETIME       = 30 * 24 * 3600; // 30 дней

    public function __construct(private UserRepository $users)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /** Текущий пользователь: из сессии или по cookie remember (автовход). */
    public function user(): ?array
    {
        if (isset($_SESSION['user_id'])) {
            $user = $this->users->findById((int)$_SESSION['user_id']);
            if ($user !== null) {
                return $user;
            }
        }

        $token = $_COOKIE[self::COOKIE_REMEMBER] ?? '';
        if ($token !== '') {
            $user = $this->users->findByRememberHash(hash('sha256', $token));
            if ($user !== null) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $this->sendSettingsCookies($user['bg_color'], $user['text_color']);
                return $user;
            }
            $this->deleteCookie(self::COOKIE_REMEMBER); // токен устарел
        }
        return null;
    }

    /** Регистрация. Возвращает текст ошибки или null при успехе. */
    public function register(string $login, string $password, string $bg, string $text): ?string
    {
        if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $login)) {
            return 'Логин: от 3 до 50 символов (латиница, цифры, _)';
        }
        if (mb_strlen($password) < 6) {
            return 'Пароль должен быть не короче 6 символов';
        }
        if ($this->users->findByLogin($login) !== null) {
            return 'Пользователь с таким логином уже существует';
        }
        $id = $this->users->create($login, $password, self::color($bg, '#ffffff'),
                                   self::color($text, '#000000'));
        $this->loginUser($this->users->findById($id));
        return null;
    }

    /** Авторизация по логину и паролю. */
    public function login(string $login, string $password): bool
    {
        $user = $this->users->findByLogin($login);
        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        $this->loginUser($user);
        return true;
    }

    /** Изменение настроек: сохраняем в БД и отправляем клиенту cookie. */
    public function saveSettings(array $user, string $bg, string $text): void
    {
        $bg   = self::color($bg, $user['bg_color']);
        $text = self::color($text, $user['text_color']);
        $this->users->updateSettings((int)$user['id'], $bg, $text);
        $this->sendSettingsCookies($bg, $text);
    }

    public function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->users->setRememberHash((int)$_SESSION['user_id'], null);
        }
        $_SESSION = [];
        session_destroy();
        foreach ([self::COOKIE_REMEMBER, self::COOKIE_BG, self::COOKIE_TEXT, session_name()] as $name) {
            $this->deleteCookie($name);
        }
    }

    private function loginUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];

        // токен «запомнить меня»: в cookie – сам токен, в БД – его хеш
        $token = bin2hex(random_bytes(32));
        $this->users->setRememberHash((int)$user['id'], hash('sha256', $token));
        setcookie(self::COOKIE_REMEMBER, $token, [
            'expires'  => time() + self::LIFETIME,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $this->sendSettingsCookies($user['bg_color'], $user['text_color']);
    }

    private function sendSettingsCookies(string $bg, string $text): void
    {
        $opts = ['expires' => time() + self::LIFETIME, 'path' => '/', 'samesite' => 'Lax'];
        setcookie(self::COOKIE_BG, $bg, $opts);
        setcookie(self::COOKIE_TEXT, $text, $opts);
        $_COOKIE[self::COOKIE_BG]   = $bg;
        $_COOKIE[self::COOKIE_TEXT] = $text;
    }

    private function deleteCookie(string $name): void
    {
        setcookie($name, '', ['expires' => time() - 3600, 'path' => '/']);
        unset($_COOKIE[$name]);
    }

    /** Проверка цвета вида #rrggbb. */
    public static function color(string $value, string $default): string
    {
        return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : $default;
    }
}

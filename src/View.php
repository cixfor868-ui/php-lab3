<?php

namespace App;

/**
 * Общий шаблон страницы. Цвета берутся из cookie, полученных от сервера.
 */
class View
{
    public static function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }

    public static function header(string $title): void
    {
        $bg   = Auth::color($_COOKIE[Auth::COOKIE_BG] ?? '', '#ffffff');
        $text = Auth::color($_COOKIE[Auth::COOKIE_TEXT] ?? '', '#000000');
        header('Content-Type: text/html; charset=utf-8');
        echo <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>{$title} — ЛР3</title>
<style>
  body { background: {$bg}; color: {$text}; font-family: Arial, sans-serif; margin: 40px; }
  form { max-width: 320px; }
  label { display: block; margin: 10px 0 4px; }
  input[type=text], input[type=password] { width: 100%; padding: 6px; box-sizing: border-box; }
  button { margin-top: 14px; padding: 6px 16px; }
  .error { color: #c00; } .ok { color: #080; }
  a { color: inherit; }
</style>
</head>
<body>
HTML;
    }

    public static function footer(): void
    {
        echo "\n</body>\n</html>\n";
    }

    public static function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}

<?php

namespace App;

use PDO;

/**
 * Работа с таблицей users.
 */
class UserRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByLogin(string $login): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE login = :login');
        $stmt->execute([':login' => $login]);
        return $stmt->fetch() ?: null;
    }

    public function findByRememberHash(string $hash): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE remember_hash = :hash');
        $stmt->execute([':hash' => $hash]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $login, string $password, string $bg, string $text): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (login, password_hash, bg_color, text_color)
             VALUES (:login, :hash, :bg, :text)'
        );
        $stmt->execute([
            ':login' => $login,
            ':hash'  => password_hash($password, PASSWORD_DEFAULT),
            ':bg'    => $bg,
            ':text'  => $text,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateSettings(int $id, string $bg, string $text): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET bg_color = :bg, text_color = :text WHERE id = :id'
        );
        $stmt->execute([':bg' => $bg, ':text' => $text, ':id' => $id]);
    }

    public function setRememberHash(int $id, ?string $hash): void
    {
        $stmt = $this->db->prepare('UPDATE users SET remember_hash = :hash WHERE id = :id');
        $stmt->execute([':hash' => $hash, ':id' => $id]);
    }
}

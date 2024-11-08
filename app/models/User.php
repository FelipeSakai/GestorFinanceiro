<?php

namespace App\Models;

use PDO;

class User
{
    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($username = null, $email = null, $password = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password ? password_hash($password, PASSWORD_BCRYPT) : null;
    }

    public function save()
    {
        $db = Database::getConnection();

        if ($this->id) {
            $stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        }

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        return $stmt->execute();
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, username, email, created_at FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, username, email, created_at FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

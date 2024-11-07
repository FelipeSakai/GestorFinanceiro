<?php

namespace App\Models;

use PDO;

class User
{
    public $id;
    public $nome;
    public $email;
    public $senha;

    public function __construct($nome = null, $email = null, $senha = null)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha ? password_hash($senha, PASSWORD_BCRYPT) : null;
    }

    public function save()
    {
        $db = Database::getConnection();

        if ($this->id) {
            $stmt = $db->prepare("UPDATE users SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)");
        }

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);

        return $stmt->execute();
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>
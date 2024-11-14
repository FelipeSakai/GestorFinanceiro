<?php

namespace App\Models;

use PDO;

class BankAccount
{
    public $id;
    public $nome;
    public $saldo;
    public $user_id;

    public function __construct($nome = null, $saldo = 0.0, $user_id = null)
    {
        $this->nome = $nome;
        $this->saldo = $saldo;
        $this->user_id = $user_id; 
    }

    public function save()
    {
        $db = Database::getConnection();

        if ($this->id) {
            $stmt = $db->prepare("UPDATE bank_accounts SET nome = :nome, saldo = :saldo, user_id = :user_id WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO bank_accounts (nome, saldo, user_id) VALUES (:nome, :saldo, :user_id)");
        }

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':saldo', $this->saldo);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }

    public function updateSaldo()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE bank_accounts SET saldo = :saldo WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':saldo', $this->saldo);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }

    public static function getAll()
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM bank_accounts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM bank_accounts WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM bank_accounts WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getByUserId($user_id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM bank_accounts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIdAndUser($id, $user_id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM bank_accounts WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function deleteByIdAndUser($id, $user_id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM bank_accounts WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

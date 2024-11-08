<?php

namespace App\Models;

use PDO;

class BankAccount
{
    public $id;
    public $nome;
    public $saldo;

    public function __construct($nome = null, $saldo = 0.0)
    {
        $this->nome = $nome;
        $this->saldo = $saldo;
    }

    public function save()
    {
        $db = Database::getConnection();

        if ($this->id) {
            $stmt = $db->prepare("UPDATE bank_accounts SET nome = :nome, saldo = :saldo WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO bank_accounts (nome, saldo) VALUES (:nome, :saldo)");
        }

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':saldo', $this->saldo);

        return $stmt->execute();
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM bank_accounts");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM bank_accounts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM bank_accounts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

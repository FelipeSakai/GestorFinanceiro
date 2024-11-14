<?php

namespace App\Models;

use PDO;

class Transaction
{
    public $account_id;
    public $value;
    public $date;

    public function __construct($account_id, $value)
    {
        $this->account_id = $account_id;
        $this->value = $value;
        $this->date = date("Y-m-d H:i:s");
    }

    public function save()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO transactions (account_id, value, date) VALUES (:account_id, :value, :date)");
        $stmt->bindParam(':account_id', $this->account_id, PDO::PARAM_INT);
        $stmt->bindParam(':value', $this->value);
        $stmt->bindParam(':date', $this->date);
    
        if (!$stmt->execute()) {
            error_log("Erro ao inserir transação: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
        return true;
    }

    public static function getTransactionsByAccountId($account_id)
{
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM transactions WHERE account_id = :account_id ORDER BY date DESC");
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->execute();

    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$transactions) {
        error_log("Nenhuma transação encontrada para a conta ID: $account_id");
    }
    return $transactions;
}

}

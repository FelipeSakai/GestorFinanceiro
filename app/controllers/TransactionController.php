<?php

namespace App\Controllers;

use App\Models\Transaction;


class TransactionController
{
   
    public function createTransaction($id)
    {

        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($id)) {
            echo json_encode(['message' => 'ID é obrigatório']);
            http_response_code(400);
            return;
        }

        $account = new Transaction();

        if ($account->save($id, $data['value'])) {
            echo json_encode(['message' => 'Conta bancária criada com sucesso']);
            http_response_code(201);
        } else {
            echo json_encode(['message' => 'Erro ao criar conta bancária']);
            http_response_code(500);
        }
    }
    public function getByAccountId($account_id)
    {
        $transaction = new Transaction();

        $transactions = $transaction->getTransactionsByAccountId($account_id);

        if ($transactions) {
            echo json_encode($transactions);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Erro ao buscar transações']);
            http_response_code(500);
        }
    }
}
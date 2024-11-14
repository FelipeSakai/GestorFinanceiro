<?php

namespace App\Transaction;

use App\Models\BankAccount;
use App\Models\Transaction;
class TransactionController
{


    public function addTransaction($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['value']) || !is_numeric($data['value'])) {
            echo json_encode(['message' => 'Valor da transação é obrigatório e deve ser numérico']);
            http_response_code(400);
            return;
        }

        $account = BankAccount::find($id);
        if (!$account) {
            echo json_encode(['message' => 'Conta bancária não encontrada']);
            http_response_code(404);
            return;
        }

        error_log("Dados da transação: " . json_encode($data));

        $transaction = new Transaction($id, $data['value']);
        if ($transaction->save()) {
            $account->saldo += $data['value'];

            if ($account->updateSaldo()) {
                echo json_encode(['message' => 'Transação registrada com sucesso', 'new_balance' => $account->saldo]);
                http_response_code(200);
            } else {
                echo json_encode(['message' => 'Erro ao atualizar o saldo da conta']);
                http_response_code(500);
            }
        } else {
            echo json_encode(['message' => 'Erro ao registrar transação']);
            http_response_code(500);
        }
    }
}
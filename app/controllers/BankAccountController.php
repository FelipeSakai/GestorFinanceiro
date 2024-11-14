<?php

namespace App\Controllers;

use App\Models\BankAccount;


class BankAccountController
{
    public function createBankAccount()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['nome']) || !isset($data['saldo'])) {
            echo json_encode(['message' => 'Nome da conta e saldo inicial são obrigatórios']);
            http_response_code(400);
            return;
        }

        $account = new BankAccount($data['nome'], $data['saldo']);

        if ($account->save()) {
            echo json_encode(['message' => 'Conta bancária criada com sucesso']);
            http_response_code(201);
        } else {
            echo json_encode(['message' => 'Erro ao criar conta bancária']);
            http_response_code(500);
        }
    }

    public function getAll()
    {
        $accounts = BankAccount::getAll();

        if ($accounts) {
            echo json_encode($accounts);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Erro ao buscar contas bancárias']);
            http_response_code(500);
        }
    }

    public function getBankAccountById($id)
    {
        $account = BankAccount::find($id);

        if ($account) {
            echo json_encode($account);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Conta bancária não encontrada']);
            http_response_code(404);
        }
    }

    public function deleteBankAccount($id)
    {
        if (BankAccount::delete($id)) {
            echo json_encode(['message' => 'Conta bancária deletada com sucesso']);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Erro ao deletar conta bancária']);
            http_response_code(500);
        }
    }
    public function updateBankAccount($id)
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['nome']) && !isset($data['saldo'])) {
        echo json_encode(['message' => 'Nome ou saldo são obrigatórios para atualização']);
        http_response_code(400);
        return;
    }

    $account = BankAccount::find($id);
    if (!$account) {
        echo json_encode(['message' => 'Conta bancária não encontrada']);
        http_response_code(404);
        return;
    }
    if (isset($data['nome'])) {
        $account->nome = $data['nome'];
    }
    if (isset($data['saldo'])) {
        $account->saldo = $data['saldo'];
    }

    if ($account->update()) {
        echo json_encode(['message' => 'Conta bancária atualizada com sucesso', 'updated_account' => $account]);
        http_response_code(200);
    } else {
        echo json_encode(['message' => 'Erro ao atualizar conta bancária']);
        http_response_code(500);
    }
}

}



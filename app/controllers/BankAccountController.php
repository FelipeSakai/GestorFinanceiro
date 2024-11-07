<?php

namespace App\Controllers;

use App\Models\BankAccount;

class BankAccountController {
    public function store() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nome']) || empty($data['saldo'])) {
            http_response_code(400);
            echo json_encode(["error" => "Nome da conta e saldo inicial são obrigatórios."]);
            return;
        }

        $account = new BankAccount($data['nome'], $data['saldo']);
        $account->save();
        echo json_encode(["success" => "Conta bancária cadastrada com sucesso."]);
    }

    public function index() {
        $accounts = BankAccount::getAll();
        echo json_encode($accounts);
    }

    public function show($id) {
        $account = BankAccount::find($id);
        if ($account) {
            echo json_encode($account);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Conta bancária não encontrada."]);
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $account = BankAccount::find($id);

        if ($account) {
            $account->nome = $data['nome'] ?? $account->nome;
            $account->saldo = isset($data['saldo']) ? floatval($data['saldo']) : $account->saldo;
            $account->save();
            echo json_encode(["success" => "Conta bancária atualizada com sucesso."]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Conta bancária não encontrada."]);
        }
    }

    public function delete($id) {
        if (BankAccount::delete($id)) {
            echo json_encode(["success" => "Conta bancária deletada com sucesso."]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Conta bancária não encontrada."]);
        }
    }
}
?>

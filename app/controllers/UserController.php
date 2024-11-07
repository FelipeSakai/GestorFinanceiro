<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            http_response_code(400);
            echo json_encode(["error" => "Nome, email e senha são obrigatórios."]);
            return;
        }

        $user = new User($data['nome'], $data['email'], $data['senha']);
        $user->save();
        echo json_encode(["success" => "Usuário cadastrado com sucesso."]);
    }

    public function index()
    {
        $users = User::getAll();
        echo json_encode($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Usuário não encontrado."]);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = User::find($id);

        if ($user) {
            $user->nome = $data['nome'] ?? $user->nome;
            $user->email = $data['email'] ?? $user->email;
            $user->senha = isset($data['senha']) ? password_hash($data['senha'], PASSWORD_BCRYPT) : $user->senha;
            $user->save();
            echo json_encode(["success" => "Usuário atualizado com sucesso."]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Usuário não encontrado."]);
        }
    }

    public function delete($id)
    {
        if (User::delete($id)) {
            echo json_encode(["success" => "Usuário deletado com sucesso."]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Usuário não encontrado."]);
        }
    }
}
?>
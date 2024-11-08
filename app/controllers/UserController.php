<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function createUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['email'], $data['password'])) {
            echo json_encode(['message' => 'Dados incompletos']);
            http_response_code(400);
            return;
        }

        $user = new User($data['username'], $data['email'], $data['password']);

        if ($user->save()) {
            echo json_encode(['message' => 'Usuário criado com sucesso']);
            http_response_code(201);
        } else {
            echo json_encode(['message' => 'Erro ao criar usuário']);
            http_response_code(500);
        }
    }

    public function getAll()
    {
        try {
            $users = User::getAll();
            echo json_encode($users);
            http_response_code(200);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'Erro ao buscar usuários']);
            http_response_code(500);
        }
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if ($user) {
            echo json_encode($user);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Usuário não encontrado']);
            http_response_code(404);
        }
    }

    public function updateUser($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['email'])) {
            echo json_encode(['message' => 'Dados incompletos']);
            http_response_code(400);
            return;
        }

        $user = User::find($id);
        if ($user) {
            $user->username = $data['username'];
            $user->email = $data['email'];

            if ($user->save()) {
                echo json_encode(['message' => 'Usuário atualizado com sucesso']);
                http_response_code(200);
            } else {
                echo json_encode(['message' => 'Erro ao atualizar usuário']);
                http_response_code(500);
            }
        } else {
            echo json_encode(['message' => 'Usuário não encontrado']);
            http_response_code(404);
        }
    }

    public function deleteUser($id)
    {
        if (User::delete($id)) {
            echo json_encode(['message' => 'Usuário deletado com sucesso']);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Erro ao deletar usuário']);
            http_response_code(500);
        }
    }
}

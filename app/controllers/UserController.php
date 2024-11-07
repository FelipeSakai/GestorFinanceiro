<?php

namespace App\Controllers;

use App\Models\Database;
use PDO;

class UserController
{

    public function createUser()
    {
        $db = Database::getConnection();
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['email'], $data['password'])) {
            echo json_encode(['message' => 'Dados incompletos']);
            http_response_code(400);
            return;
        }

        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_BCRYPT));
            $stmt->execute();

            echo json_encode(['message' => 'Usuário criado com sucesso']);
            http_response_code(201);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'Erro ao criar usuário']);
            http_response_code(500);
        }
    }

    public function getAll()
    {
        $db = Database::getConnection();

        try {
            $stmt = $db->query("SELECT id, username, email, created_at FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($users);
            http_response_code(200);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'Erro ao buscar usuários']);
            http_response_code(500);
        }
    }

    public function getUserById($id)
    {
        $db = Database::getConnection();

        try {
            $stmt = $db->prepare("SELECT id, username, email, created_at FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode($user);
                http_response_code(200);
            } else {
                echo json_encode(['message' => 'Usuário não encontrado']);
                http_response_code(404);
            }
        } catch (\Exception $e) {
            echo json_encode(['message' => 'Erro ao buscar usuário']);
            http_response_code(500);
        }
    }

    public function updateUser($id)
    {
        $db = Database::getConnection();
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['email'])) {
            echo json_encode(['message' => 'Dados incompletos']);
            http_response_code(400);
            return;
        }

        try {
            $stmt = $db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['message' => 'Usuário atualizado com sucesso']);
            http_response_code(200);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'Erro ao atualizar usuário']);
            http_response_code(500);
        }
    }

    public function deleteUser($id)
    {
        $db = Database::getConnection();

        try {
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['message' => 'Usuário deletado com sucesso']);
            http_response_code(200);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'Erro ao deletar usuário']);
            http_response_code(500);
        }
    }
}
?>
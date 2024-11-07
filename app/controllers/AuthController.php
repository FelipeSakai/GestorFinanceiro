<?php

namespace App\Controllers;

use app\models\User;
use app\utils\SessionManager;

class AuthController {
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['senha'])) {
            http_response_code(400);
            echo json_encode(["error" => "Email e senha são obrigatórios."]);
            return;
        }

        $user = User::findByEmail($data['email']);
        if ($user && password_verify($data['senha'], $user->senha)) {
            $token = SessionManager::generateToken($user->id);
            echo json_encode(["success" => "Login realizado com sucesso.", "token" => $token]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Credenciais inválidas."]);
        }
    }
}
?>

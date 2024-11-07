<?php

use App\Controllers\UserController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestUri) {
    case '/users':
        $controller = new UserController();

        if ($requestMethod === 'GET') {
            $controller->getAll();
        } elseif ($requestMethod === 'POST') {
            $controller->createUser();
        }
        break;

    case (preg_match('/\/users\/(\d+)/', $requestUri, $matches) ? true : false): 
        $controller = new UserController();
        $userId = $matches[1];

        if ($requestMethod === 'GET') {
            $controller->getUserById($userId);
        } elseif ($requestMethod === 'PUT') {
            $controller->updateUser($userId);
        } elseif ($requestMethod === 'DELETE') {
            $controller->deleteUser($userId);
        }
        break;

    default:
        echo json_encode(['message' => 'Rota nao encontrada']);
        http_response_code(404);
        break;
}
?>

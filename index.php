<?php

require_once './vendor/autoload.php';

use App\controllers\UserController;
use App\controllers\BankAccountController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestUri) {
   
    case '/users':
        $controller = new UserController();

        if ($requestMethod == 'GET') {
            $controller->getAll();
        } elseif ($requestMethod == 'POST') {
            $controller->createUser();
        }
        break;

    case (preg_match('/\/users\/(\d+)/', $requestUri, $matches) ? true : false): 
        $controller = new UserController();
        $userId = $matches[1];

        if ($requestMethod == 'GET') {
            $controller->getUserById($userId);
        } elseif ($requestMethod == 'PUT') {
            $controller->updateUser($userId);
        } elseif ($requestMethod == 'DELETE') {
            $controller->deleteUser($userId);
        }
        break;

    case '/bank_accounts':
        $controller = new BankAccountController();

        if ($requestMethod == 'GET') {
            $controller->getAll();
        } elseif ($requestMethod == 'POST') {
            $controller->createBankAccount();
        }
        break;

    case (preg_match('/\/bank_accounts\/(\d+)/', $requestUri, $matches) ? true : false): 
        $controller = new BankAccountController();
        $accountId = $matches[1];

        if ($requestMethod == 'GET') {
            $controller->getBankAccountById($accountId);
        } elseif ($requestMethod == 'PUT') {
            $controller->updateBankAccount($accountId);
        } elseif ($requestMethod == 'DELETE') {
            $controller->deleteBankAccount($accountId);
        }
        break;

    default:
        echo json_encode(['message' => 'Rota não encontrada']);
        http_response_code(404);
        break;
}

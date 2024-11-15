<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Access-Control-Allow-Credentials: true");
require_once './vendor/autoload.php';

http_response_code(200);

use App\controllers\UserController;
use App\controllers\BankAccountController;
use App\controllers\TransactionController;

$requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$pathItems = array_filter(explode("/", $requestedPath)); // Remove elementos vazios

// Reconstrói o caminho sem pressupor a posição
$requestedPath = "/" . ($pathItems[1] ?? '') . (!empty($pathItems[2]) ? "/" . $pathItems[2] : "");
$requestMethod = $_SERVER['REQUEST_METHOD'];


switch ($requestedPath) {

    case '/users':
        $controller = new UserController();

        if ($requestMethod == 'GET') {
            $controller->getAll();
        } elseif ($requestMethod == 'POST') {
            $controller->createUser();
        }
        break;

    case (preg_match('/\/users\/(\d+)/', $requestedPath, $matches) ? true : false):
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

    case '/bank_accounts/transaction':
        $controller = new BankAccountController();

        if ($requestMethod == 'GET') {
            $controller->getAll();
        } elseif ($requestMethod == 'POST') {
            $controller->createBankAccount();
        }
        break;

    case (preg_match('/\/bank_accounts\/(\d+)/', $requestedPath, $matches) ? true : false):
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
    case (preg_match('/\/bank_accounts\/\/transaction\/(\d+)$/', $requestedPath, $matches) ? true : false):
        $controller = new TransactionController();
        $accountId = $matches[1];

        if ($requestMethod == 'POST') {
            $controller->createTransaction($accountId);
        } else if ($requestMethod == 'GET') {
            $controller->getByAccountId($accountId);
        }
        break;

    default:
        echo json_encode(['message' => 'Rota não encontrada']);
        http_response_code(404);
        break;
}

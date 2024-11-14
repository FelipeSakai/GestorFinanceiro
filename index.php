<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once './vendor/autoload.php';

use App\controllers\UserController;
use App\controllers\BankAccountController;

$requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$pathItems = explode("/", $requestedPath);

$requestedPath = "/" . ($pathItems[3] ?? '') . (!empty($pathItems[4]) ? "/" . $pathItems[4] : "");
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

    case (preg_match('/\/bank_accounts\/(\d+)\/transaction/', $requestedPath, $matches) ? true : false): 
        $controller = new BankAccountController();
        $accountId = $matches[1];

        if ($requestMethod == 'POST') {
            $controller->addTransaction($accountId);
        }
        break;

    default:
        echo json_encode(['message' => 'Rota não encontrada']);
        http_response_code(404);
        break;
}

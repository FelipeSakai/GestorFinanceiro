<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/router/routes.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

Route::resolve($requestUri, $requestMethod);
?>
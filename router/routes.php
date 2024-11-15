<?php

class Route
{
    private static $routes = [];

    public static function add($method, $uri, $action)
    {
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public static function resolve($requestUri, $requestMethod)
    {
        foreach (self::$routes as $route) {
            if ($route['method'] == $requestMethod && preg_match("#^{$route['uri']}$#", $requestUri, $matches)) {
                $action = explode('@', $route['action']);
                $controller = "App\\Controllers\\" . $action[0];
                $method = $action[1];
                $controllerInstance = new $controller();
                return call_user_func_array([$controllerInstance, $method], array_slice($matches, 1));
            }
        }
        http_response_code(404);
        echo json_encode(["error" => "404 - Not Found"]);
    }
}

Route::add('POST', '/users', 'UserController@store');
Route::add('GET', '/users', 'UserController@index');
Route::add('GET', '/users/(\d+)', 'UserController@show');
Route::add('PUT', '/users/(\d+)', 'UserController@update');
Route::add('DELETE', '/users/(\d+)', 'UserController@delete');

Route::add('GET', '/accounts', 'BankAccountController@getAll');
Route::add('POST', '/accounts', 'BankAccountController@store');
Route::add('GET', '/accounts', 'BankAccountController@index');
Route::add('GET', '/accounts/(\d+)', 'BankAccountController@show');
Route::add('PUT', '/accounts/(\d+)', 'BankAccountController@update');
Route::add('DELETE', '/accounts/(\d+)', 'BankAccountController@delete');
Route::add('POST', '/accounts/(\d+)/transaction', 'BankAccountController@addTransaction');

?>
<?php

$baseUri = '/technical_assignment';

define('GET_INDEX_ENDPOINT', $baseUri . '/home');
define('INDEX_ENDPOINT', $baseUri . '/filter');
define('GET_LOGIN_ENDPOINT', $baseUri . '/login');
define('LOGIN_ENDPOINT', $baseUri . '/login_form');
define('LOGOUT_ENDPOINT', $baseUri . '/logout');
define('GET_CREATE_TRANSACTION_ENDPOINT', $baseUri . '/transaction');
define('CREATE_TRANSACTION_ENDPOINT', $baseUri . '/transaction_form');
define('GET_USERS_ENDPOINT', $baseUri . '/users');
define('EDIT_USERS_ENDPOINT', $baseUri . '/users_form');

require_once __DIR__ . '/../controllers/indexController.php';
require_once __DIR__ . '/../controllers/TransactionsController.php';
require_once __DIR__ . '/../controllers/usersController.php';
require_once __DIR__ . '/../controllers/Auth/loginController.php';


class Web
{
    private $routes = [];

    public function __construct()
    {
        $this->routes = [
            GET_INDEX_ENDPOINT => ['controller' => new IndexController(), 'method' => 'index', 'request_method' => 'GET'],
            INDEX_ENDPOINT => ['controller' => new IndexController(), 'method' => 'filter', 'request_method' => 'POST'],
            GET_LOGIN_ENDPOINT => ['controller' => new loginController(), 'method' => 'index', 'request_method' => 'GET'],
            LOGIN_ENDPOINT => ['controller' => new loginController(), 'method' => 'login', 'request_method' => 'POST'],
            LOGOUT_ENDPOINT => ['controller' => new loginController(), 'method' => 'logout', 'request_method' => 'GET'],
            GET_CREATE_TRANSACTION_ENDPOINT => ['controller' => new TransactionsController(), 'method' => 'index', 'request_method' => 'GET'],
            CREATE_TRANSACTION_ENDPOINT => ['controller' => new TransactionsController(), 'method' => 'createNewTransaction', 'request_method' => 'POST'],
            GET_USERS_ENDPOINT => ['controller' => new usersController(), 'method' => 'index', 'request_method' => 'GET'],
            EDIT_USERS_ENDPOINT => ['controller' => new usersController(), 'method' => 'user', 'request_method' => 'POST'],
        ];
    }

    public function handleRequest()
    {
        //TODO si es LOGIN_ENDPOINT,GET_LOGIN_ENDPOINT,GET_USERS_ENDPOINT o EDIT_USERS_ENDPOINT no dejar pasar
        $routeFound = false;
        foreach ($this->routes as $route => $config) {

            if ($_SERVER['REQUEST_URI'] == $route && $_SERVER['REQUEST_METHOD'] == $config['request_method']) {
                $response = $config['controller']->{$config['method']}();
                echo $response;
                $routeFound = true;
                break;
            }
        }

        if (!$routeFound) {
            echo json_encode(['error' => 'Route not found']);
        }
    }
}

?>

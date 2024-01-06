<?php

require_once __DIR__ . '/../controllers/Auth/AuthController.php';
require_once __DIR__ . '/../controllers/RolesController.php';
require_once __DIR__ . '/../controllers/TransactionController.php';

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';


$authController = new AuthController();
$roleController = new RolesController();
$transactionController = new TransactionController();

$authController = new AuthController();

$tokenValidator = new AuthMiddleware();

$baseUri = '/technical_assignment';
$token = $headers['Authorization'] ?? null;

//TODO dont work validation
// $tokenValidator->validateToken($token);
// echo "Token válido.";

define('REGISTER_ENDPOINT', $baseUri . '/register');
define('LOGIN_ENDPOINT', $baseUri . '/login');
define('CREATE_ROLE_ENDPOINT', $baseUri . '/create-role');
define('ASSIGN_ROLE_ENDPOINT', $baseUri . '/assign-role');
define('CREATE_TRANSACTION_ENDPOINT', $baseUri . '/create-transaction');
define('GET_ALL_TRANSACTIONS_ENDPOINT', $baseUri . '/transactions');
define('GET_TRANSACTIONS_BY_DATE_ENDPOINT', $baseUri . '/transactions-by-date');

$routes = [
    REGISTER_ENDPOINT => ['controller' => $authController, 'method' => 'register', 'request_method' => 'POST'],
    LOGIN_ENDPOINT => ['controller' => $authController, 'method' => 'login', 'request_method' => 'POST'],
    CREATE_ROLE_ENDPOINT => ['controller' => $roleController, 'method' => 'createRole', 'request_method' => 'POST'],
    ASSIGN_ROLE_ENDPOINT => ['controller' => $roleController, 'method' => 'assignRoleToUser', 'request_method' => 'POST'],
    CREATE_TRANSACTION_ENDPOINT => ['controller' => $transactionController, 'method' => 'createNewTransaction', 'request_method' => 'POST'],
    GET_ALL_TRANSACTIONS_ENDPOINT => ['controller' => $transactionController, 'method' => 'getAllTransactions', 'request_method' => 'GET'],
    GET_TRANSACTIONS_BY_DATE_ENDPOINT => ['controller' => $transactionController, 'method' => 'getTransactionsByDate', 'request_method' => 'POST']
];

$routeFound = false;
header('Content-Type: application/json');

foreach ($routes as $route => $config) {
    if ($_SERVER['REQUEST_URI'] == $route && $_SERVER['REQUEST_METHOD'] == $config['request_method']) {
        $response = $config['controller']->{$config['method']}();
        $routeFound = true;
        echo json_encode($response);
        break;
    }
}

if (!$routeFound) {
    echo json_encode(['error' => 'Route not found']);
}

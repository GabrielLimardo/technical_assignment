<?php

$baseUri = '/technical_assignment/api';

define('API_REGISTER_ENDPOINT', $baseUri . '/register');
define('API_LOGIN_ENDPOINT', $baseUri . '/login');
define('API_CREATE_ROLE_ENDPOINT', $baseUri . '/create-role');
define('API_ASSIGN_ROLE_ENDPOINT', $baseUri . '/assign-role');
define('API_CREATE_TRANSACTION_ENDPOINT', $baseUri . '/create-transaction');
define('API_GET_ALL_TRANSACTIONS_ENDPOINT', $baseUri . '/transactions');
define('API_GET_TRANSACTIONS_BY_DATE_ENDPOINT', $baseUri . '/transactions-by-date');

require_once __DIR__ . '/../controllers/api/Auth/AuthController.php';
require_once __DIR__ . '/../controllers/api/RolesController.php';
require_once __DIR__ . '/../controllers/api/TransactionController.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class Api
{
    private $routes = [];
    private $tokenValidator;

    public function __construct()
    {
        $this->tokenValidator = new AuthMiddleware();

        $this->routes = [
            API_REGISTER_ENDPOINT => ['controller' => new AuthController(), 'method' => 'register', 'request_method' => 'POST'],
            API_LOGIN_ENDPOINT => ['controller' => new AuthController(), 'method' => 'login', 'request_method' => 'POST'],
            API_CREATE_ROLE_ENDPOINT => ['controller' => new RolesController(), 'method' => 'createRole', 'request_method' => 'POST'],
            API_ASSIGN_ROLE_ENDPOINT => ['controller' => new RolesController(), 'method' => 'assignRoleToUser', 'request_method' => 'POST'],
            API_CREATE_TRANSACTION_ENDPOINT => ['controller' => new TransactionController(), 'method' => 'createNewTransaction', 'request_method' => 'POST'],
            API_GET_ALL_TRANSACTIONS_ENDPOINT => ['controller' => new TransactionController(), 'method' => 'getAllTransactions', 'request_method' => 'GET'],
            API_GET_TRANSACTIONS_BY_DATE_ENDPOINT => ['controller' => new TransactionController(), 'method' => 'getTransactionsByDate', 'request_method' => 'POST']
        ];
    }

    public function handleRequest()
    {
        try {
            header('Content-Type: application/json');
            $routeFound = false;
            $headers = apache_request_headers();
            $token = $headers['Authorization'] ?? null;

            foreach ($this->routes as $route => $config) {
                if ($_SERVER['REQUEST_URI'] == $route && $_SERVER['REQUEST_METHOD'] == $config['request_method']) {
                    if (in_array($route, [API_REGISTER_ENDPOINT, API_CREATE_ROLE_ENDPOINT, API_ASSIGN_ROLE_ENDPOINT])) {
                        $isValid = $this->tokenValidator->validateToken($token);
                        if (!$isValid) {
                            echo json_encode(['error' => 'Validation failed']);
                            exit;
                        }
                    }
                    $response = $config['controller']->{$config['method']}();
                    echo json_encode($response);
                    $routeFound = true;
                    break;
                }
            }

            if (!$routeFound) {
                echo json_encode(['error' => 'Route not found']);
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

?>

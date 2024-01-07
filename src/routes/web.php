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
define('GENERATE_REPORT', $baseUri . '/generate-report');


require_once __DIR__ . '/../controllers/indexController.php';
require_once __DIR__ . '/../controllers/TransactionsController.php';
require_once __DIR__ . '/../controllers/usersController.php';
require_once __DIR__ . '/../controllers/Auth/loginController.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

$requestUri = $_SERVER['REQUEST_URI'];
if ($requestUri === '/technical_assignment/' | $requestUri === '/technical_assignment') {
    header('Location:/technical_assignment/home');
    exit;
}

class Web
{
    private $routes = [];
    private $validator;

    public function __construct()
    {
        $this->validator = new AuthMiddleware();

        $this->routes = [
            GET_INDEX_ENDPOINT => ['controller' => new IndexController(), 'method' => 'index', 'request_method' => 'GET'],
            INDEX_ENDPOINT => ['controller' => new IndexController(), 'method' => 'filter', 'request_method' => 'POST'],
            GET_LOGIN_ENDPOINT => ['controller' => new loginController(), 'method' => 'index', 'request_method' => 'GET'],
            LOGIN_ENDPOINT => ['controller' => new loginController(), 'method' => 'login', 'request_method' => 'POST'],
            LOGOUT_ENDPOINT => ['controller' => new loginController(), 'method' => 'logout', 'request_method' => 'GET'],
            GET_CREATE_TRANSACTION_ENDPOINT => ['controller' => new TransactionsController(), 'method' => 'index', 'request_method' => 'GET'],
            CREATE_TRANSACTION_ENDPOINT => ['controller' => new TransactionsController(), 'method' => 'createNewTransaction', 'request_method' => 'POST'],
            GET_USERS_ENDPOINT => ['controller' => new usersController(), 'method' => 'index', 'request_method' => 'GET'],
            EDIT_USERS_ENDPOINT => ['controller' => new usersController(), 'method' => 'edit', 'request_method' => 'POST'],
            GENERATE_REPORT => ['controller' => new IndexController(), 'method' => 'generateBalance', 'request_method' => 'GET'],
        ];
    }

    public function handleRequest()
    {
        try {

            $protectedEndpoints = [
                GET_USERS_ENDPOINT,
                EDIT_USERS_ENDPOINT
            ];

            $routeFound = false;
            if (in_array($_SERVER['REQUEST_URI'], $protectedEndpoints) && !$this->validator->handle()) {
                header('Location: ' . GET_LOGIN_ENDPOINT);
                exit;
            }

            foreach ($this->routes as $route => $config) {

                if ($_SERVER['REQUEST_URI'] == $route && $_SERVER['REQUEST_METHOD'] == $config['request_method']) {
                    $response = $config['controller']->{$config['method']}();
                    echo $response;
                    $routeFound = true;
                    break;
                }
            }

            if (!$routeFound) {
                header('Location:/technical_assignment/home');
            }
        } catch (\Exception $e) {
            echo '<div style="color: red; margin-top: 20px;">Error inesperado: ' . $e->getMessage() . '</div>';
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
}

?>

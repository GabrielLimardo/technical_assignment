<?php

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Transaction.php';

class loginController {

    private $userModel;
    private $transactionModel;

    public function __construct() {
        $db = new Database(); 
        $this->userModel = new User($db);
        $this->transactionModel = new Transaction($db->getConnection());

    }

    public function index() {
        require_once __DIR__ . '/../../../resources/views/auth/login_view.php';
    }
    
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user_id = $this->userModel->login($username, $password);


            if ($user_id) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                $transactions = $this->transactionModel->getAllTransactions();
                require_once __DIR__ . '/../../../resources/views/index_view.php';

            } else {
                require_once __DIR__ . '/../../../resources/views/auth/login_view.php';
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        session_unset();
    
        session_destroy();
    
        header('Location: /technical_assignment/login');
        exit;
    }
}

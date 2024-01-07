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
        $this->userModel = new User($db->getConnection());
        $this->transactionModel = new Transaction($db->getConnection());

    }

    public function index() {
        try {
            require_once __DIR__ . '/../../../resources/views/auth/login_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
    
    public function login() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 5) {
                throw new \Exception('Too many failed attempts. Please try again later.');
            }
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'] ?? null;
                $password = $_POST['password'] ?? null;
    
                if (!$username || !$password) {
                    throw new \Exception('Username or password is missing.');
                }
    
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
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
            $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
            $_SESSION['login_attempts']++;
        }
    }
    
    public function logout() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            session_unset();
            session_destroy();
            header('Location: /technical_assignment/login');
            exit;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }    
}

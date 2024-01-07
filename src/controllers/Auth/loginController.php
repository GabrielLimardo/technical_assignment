<?php

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Transaction.php';
require_once __DIR__ . '/../../services/AuthService.php';

class loginController {

    private $userModel;
    private $transactionModel;
    private $authService;


    public function __construct() {
        $db = new Database(); 
        $this->userModel = new User($db->getConnection());
        $this->transactionModel = new Transaction($db->getConnection());
        $this->authService = new AuthService($this->userModel, $this->transactionModel );
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
        $this->authService->sessionLogin();
    }
    
    public function logout() {
        $this->authService->logout();
    }    
}

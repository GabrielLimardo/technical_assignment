<?php

require_once __DIR__ . '/../../../../config/Database.php';
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../services/AuthService.php';

class AuthController {
    private $userModel;
    private $authService;

    public function __construct() {
        $db = new Database();
        $this->userModel = new User($db->getConnection());
        $this->authService = new AuthService($this->userModel);
    }

    public function register() {
        return $this->authService->register();
    }
    
    public function login() {
        return $this->authService->login();
    }    
}

<?php

require_once __DIR__ . '/../../../../config/Database.php';
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $db = new Database();
        $this->userModel = new User($db->getConnection());
    }

    public function register() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'] ?? null;
                $password = $_POST['password'] ?? null;
                
                if ($username === null || $password === null) {
                    throw new \Exception('Username and password are required.');
                }
    
                $user_id = $this->userModel->register($username, $password);
    
                if ($user_id) {
                    $token = $this->userModel->generateToken($user_id);
                    return [
                        'message' => 'Registration successful.',
                        'token' => $token
                    ];
                } else {
                    throw new \Exception('Failed to register.');
                }
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'] ?? null;
                $password = $_POST['password'] ?? null;
    
                if ($username === null || $password === null) {
                    throw new \Exception('Username and password are required.');
                }
    
                $user_id = $this->userModel->login($username, $password);
                
                if ($user_id) {
                    $token = $this->userModel->generateToken($user_id);
                    return [
                        'message' => 'Login successful.',
                        'token' => $token
                    ];
                } else {
                    throw new \Exception('Incorrect credentials.');
                }
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }    
}

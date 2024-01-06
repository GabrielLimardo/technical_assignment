<?php
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User(new Database());
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user_id = $this->userModel->register($username, $password);

            if ($user_id) {
                $token = $this->userModel->generateToken($user_id);
                return [
                    'message' => 'Registration successful.',
                    'token' => $token
                ];
            } else {
                return ['error' => 'Failed to register.'];
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user_id = $this->userModel->login($username, $password);
            if ($user_id) {
                $token = $this->userModel->generateToken($user_id);
                return [
                    'message' => 'Login successful..',
                    'token' => $token
                ];
            } else {
                return ['error' => 'Incorrect credentials.'];
            }
        }
    }
}

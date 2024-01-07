<?php

class AuthService {

    protected $userModel;
    protected $transactionModel;

    public function __construct($userModel, $transactionModel = null) {
        $this->userModel = $userModel;
        $this->transactionModel = $transactionModel;
    }

    public function register(): array {
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

    public function logout(): void {
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

    public function login(): ?array {
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

    public function sessionLogin(): void {
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
                    require_once __DIR__ . '/../../resources/views/index_view.php';
                } else {
                    throw new \Exception('Incorrect credentials.');
                }
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
            $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
            $_SESSION['login_attempts']++;
        }
    }
}

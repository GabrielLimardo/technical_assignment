<?php

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/Database.php';

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthMiddleware
{
    private $db;

    public function __construct()
    {
        $db = new Database(); 
        $this->db = $db->getConnection();
    }

    public function validateToken(string $token): bool {
        try {
            $decoded = JWT::decode($token, new Key(SECRET_KEY, 'HS256'));
            $currentTimestamp = time();
            
            if ($decoded->exp < $currentTimestamp) {
                throw new \Exception("Token has expired");
            }

            if (!$this->isAdmin($decoded->user_id)) {
                throw new \Exception("User is not an admin");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error validating token: " . $e->getMessage());
        }
    }

    private function isAdmin(int $userId): bool {
        $stmt = $this->db->prepare("
            SELECT r.name 
            FROM user_roles ur 
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = :userId AND r.name = 'admin'
        ");

        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch() ? true : false;
    }

    public function handle(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /technical_assignment/login');
            exit;
        }

        return true;
    }
}


<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/Database.php';

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthMiddleware
{
    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(SECRET_KEY, 'HS256'));
            $currentTimestamp = time();
            if ($decoded->exp < $currentTimestamp) {
                throw new \Exception("Fail validation. ");
            }
            $this->checkAdmin($decoded->user_id);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error al validar");
        }
    }

    public static function isAdmin($userId, $db) {
        $stmt = $db->getConnection()->prepare("
            SELECT r.name 
            FROM user_roles ur 
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = :userId AND r.name = 'admin'
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch() ? true : false;
    }

    public function checkAdmin($user_id) {
        $db = new Database(); 

        if (isset($user_id)) {
            $userId = $user_id;
            if (!$this->isAdmin($userId, $db)) {
                throw new \Exception("Fail Admin role check");
            }
        } else {
            return ['error' => 'Fail Admin role check.'];

        }
    }

    public function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /technical_assignment/login');
            exit;
        }

        $this->checkAdmin($_SESSION['user_id']);

        return true;
    }
}

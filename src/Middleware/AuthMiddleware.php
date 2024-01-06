<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthMiddleware
{
    public function validateToken($token) {
        try {
            $key = base64_decode($token);
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $currentTimestamp = time();
            if ($decoded->exp < $currentTimestamp) {
                throw new \Exception("Fail validation. " );
            }
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error al validar: " . $e->getMessage());
        }
    }

    public static function isAdmin($userId, $pdo) {
        $stmt = $pdo->prepare("
            SELECT r.name 
            FROM user_roles ur 
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = :userId AND r.name = 'admin'
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch() ? true : false;
    }

    public function checkAdmin() {
        global $pdo;
        var_dump($_SESSION['user_id']);

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            if (!$this->isAdmin($userId, $pdo)) {
                return ['error' => 'Fail Rol.'];
            }
        } else {
            return ['error' => 'Fail Rol.'];
        }
    }

}

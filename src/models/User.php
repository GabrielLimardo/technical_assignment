<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($username, $password) {
        
        if (strlen($password) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres.");
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        $userId = $this->db->lastInsertId();
        return $userId;
    }
    
    public function login($username, $password) {
        $stmt = $this->db->prepare("
        SELECT u.*, r.name as role_name
        FROM users u
        JOIN user_roles ur ON u.id = ur.user_id
        JOIN roles r ON ur.role_id = r.id
        WHERE u.username = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            if ($user['role_name'] === 'admin') {
                return $user['id'];
            } 
        }
        return false;
    }

    public function generateToken($userId) {
        $key = SECRET_KEY;
        $payload = [
            "iss" => SECRET_KEY,
            "aud" => SECRET_KEY, 
            "iat" => time(), 
            "exp" => time() + (60 * 60 * 2),
            "user_id" => $userId
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');

        $expiresAt = date('Y-m-d H:i:s', $payload['exp']);

        $existingToken = $this->getExistingTokenByUserId($userId);

        if ($existingToken) {
            $stmt = $this->db->prepare('UPDATE tokens SET token = :token, expires_at = :expires_at WHERE user_id = :user_id');
        } else {
            $stmt = $this->db->prepare('INSERT INTO tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        }

        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $jwt,
            ':expires_at' => $expiresAt
        ]);

        return $jwt;
    }

    private function getExistingTokenByUserId($userId) {
        $stmt = $this->db->prepare('SELECT token FROM tokens WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function getAllUsers() {
        $stmt = $this->db->query("
        SELECT u.*, r.name as role 
        FROM users u 
        JOIN user_roles ru ON u.id = ru.user_id 
        JOIN roles r ON ru.role_id = r.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($userId) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role 
            FROM users u 
            JOIN user_roles ru ON u.id = ru.user_id 
            JOIN roles r ON ru.role_id = r.id 
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editUser($userId, $newUsername, $newPassword, $newRol) {

        if (strlen($newPassword) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres.");
        }

        $sql = "UPDATE users SET username = :username, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':username', $newUsername, PDO::PARAM_STR);
        $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        $stmt->closeCursor();
        
        $sqlUpdateRole = "UPDATE user_roles SET role_id = :role WHERE user_id = :id";
        $stmtUpdateRole = $this->db->prepare($sqlUpdateRole);
        
        $stmtUpdateRole->bindParam(':role', $newRol, PDO::PARAM_STR);
        $stmtUpdateRole->bindParam(':id', $userId, PDO::PARAM_INT);
        
        $stmtUpdateRole->execute();
        $stmtUpdateRole->closeCursor();
    }      
}

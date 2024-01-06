<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->getConnection()->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        $userId = $this->db->getConnection()->lastInsertId();
        return $userId;
    }
    
    public function login($username, $password) {
        $stmt = $this->db->getConnection()->prepare("
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
            $stmt = $this->db->getConnection()->prepare('UPDATE tokens SET token = :token, expires_at = :expires_at WHERE user_id = :user_id');
        } else {
            $stmt = $this->db->getConnection()->prepare('INSERT INTO tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        }

        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $jwt,
            ':expires_at' => $expiresAt
        ]);

        return $jwt;
    }

    private function getExistingTokenByUserId($userId) {
        $stmt = $this->db->getConnection()->prepare('SELECT token FROM tokens WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchColumn();
    }
}

<?php

require_once __DIR__ . '/../../../src/Middleware/AuthMiddleware.php';


use PHPUnit\Framework\TestCase;
use Firebase\JWT\JWT;

class AuthMiddlewareTest extends TestCase
{
    private $pdo;
    private $user;
    private $tokenValidator;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                username TEXT,
                password TEXT
            );
            
            CREATE TABLE user_roles (
                user_id INTEGER,
                role_id INTEGER
            );

            CREATE TABLE roles (
                id INTEGER PRIMARY KEY,
                name TEXT
            );

            CREATE TABLE tokens (
                user_id INTEGER,
                token TEXT,
                expires_at DATETIME
            );
        ");
        $this->user = new User($this->pdo);
        $this->tokenValidator = new AuthMiddleware();
    }

    public function testValidateTokenSuccess()
    {
        $userId = $this->registerAdminUser('testuser', 'password123');

        $key = SECRET_KEY;

        $tokenPayload = [
            "iss" => SECRET_KEY,
            "aud" => SECRET_KEY, 
            "iat" => time(), 
            "exp" => time() + 3600,
            "user_id" => $userId
        ];

        $token = JWT::encode($tokenPayload, $key , 'HS256');

        $this->assertTrue($this->tokenValidator->validateToken($token));
    }

    public function testValidateTokenExpired()
    {
        $key = SECRET_KEY;

        $tokenPayload = [
            "iss" => SECRET_KEY,
            "aud" => SECRET_KEY, 
            "iat" => time(), 
            "exp" => time() - 3600, 
            "user_id" => 1
        ];

        $token = JWT::encode($tokenPayload, $key , 'HS256');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error validating token: Expired token');

        $this->tokenValidator->validateToken($token);
    }

    public function registerAdminUser($username, $password): int
    {
        $userId = $this->user->register($username, $password);

        $roleName = 'admin';
        $stmt = $this->pdo->prepare("INSERT INTO roles (name) VALUES (?)");
        $stmt->execute([$roleName]);

        $stmt = $this->pdo->prepare("SELECT id FROM roles WHERE name = ?");
        $stmt->execute([$roleName]);
        $role = $stmt->fetch();
        $roleId = $role['id'];

        $stmt = $this->pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([intval($userId), $roleId]);

        return intval($userId);
    }
}

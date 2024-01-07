<?php
require_once __DIR__ . '/../../../src/models/User.php';

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $pdo;
    private $user;

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
    }

    public function testRegister(): void
    {
        $username = 'testuser';
        $password = 'password123';
    
        $this->registerAdminUser($username, $password);
    
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    
        $this->assertIsArray($user);
        $this->assertEquals($username, $user['username']);
    }
    
    public function testLogin(): void
    {
        $username = 'testuser';
        $password = 'password123';
    
        $this->registerAdminUser($username, $password);
    
        $userId = $this->user->login($username, $password);
    
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    
        $this->assertEquals($userId, $user['id']);
    }

    public function testGenerateToken(): void
    {
        $userId = $this->registerAdminUser('testuser', 'password123');
        $token = $this->user->generateToken($userId);
        $this->assertIsString($token);
    }

    public function testGetAllUsers(): void
    {
        $this->registerAdminUser('testuser', 'password123');
        $this->registerAdminUser('testuser2', 'password123');
        $users = $this->user->getAllUsers();
        $this->assertCount(2, $users);
    }

    public function testGetUserById(): void
    {
        $userId = $this->registerAdminUser('testuser', 'password123');
        $user = $this->user->getUserById($userId);
        $this->assertEquals('testuser', $user['username']);
    }

    public function testEditUser(): void
    {
        $userId = $this->registerAdminUser('testuser', 'password123');
        $this->user->editUser($userId, 'newusername', 'newpassword123', 1);
        $user = $this->user->getUserById($userId);
        $this->assertEquals('newusername', $user['username']);
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

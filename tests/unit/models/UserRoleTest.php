<?php
require_once __DIR__ . '/../../../src/models/UserRole.php';

use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    private $pdo;
    private $userRole;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                username TEXT
            );

            CREATE TABLE roles (
                id INTEGER PRIMARY KEY,
                name TEXT
            );

            CREATE TABLE user_roles (
                user_id INTEGER,
                role_id INTEGER
            );
        ");

        $this->userRole = new UserRole($this->pdo);
    }

    public function testAssignUserRole(): void
    {
        $userId = 1;
        $roleId = 1;

        $result = $this->userRole->assign($userId, $roleId);
        $this->assertTrue($result);
    }
}

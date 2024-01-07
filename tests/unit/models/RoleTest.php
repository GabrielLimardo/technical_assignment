<?php
require_once __DIR__ . '/../../../src/models/Role.php';

use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    private $pdo;
    private $role;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE roles (id INTEGER PRIMARY KEY, name TEXT)");

        $this->role = new Role($this->pdo);
    }

    public function testCreateRole(): void
    {
        $name = 'Admin';
        
        $this->assertTrue($this->role->create($name));

        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result);
        $this->assertEquals($name, $result['name']);
    }

    public function testGetAllRoles(): void
    {
        $rolesToInsert = ['Admin', 'User', 'Guest'];
        foreach ($rolesToInsert as $roleName) {
            $this->role->create($roleName);
        }

        $allRoles = $this->role->getAllRoles();

        $this->assertCount(3, $allRoles);

        $roleNames = array_column($allRoles, 'name');
        $this->assertContains('Admin', $roleNames);
    }
}

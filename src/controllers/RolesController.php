<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/UserRole.php';

class RolesController {

    private $roleModel;
    private $userRoleModel;


    public function __construct() {
        $db = new Database(); 
        $this->roleModel = new Role($db->getConnection());
        $this->userRoleModel = new UserRole($db->getConnection());
    }

    public function createRole() {
        $name = $_POST['name'] ?? null;
        if ($name === null) {
            return ['success' => false, 'message' => 'Role name is required.'] ;
        }
    
        if ($this->roleModel->create($name)) {
            return  ['success' => true, 'message' => 'Role successfully created.'];
        } else {
            return  ['success' => false, 'message' => 'Failed to create role. Please try again.'];
        }
    }
    
    public function assignRoleToUser() {
        $userId = $_POST['userId'] ?? null;
        $roleId = $_POST['roleId'] ?? null;
    
        if ($userId === null || $roleId === null) {
            return ['success' => false, 'message' => 'User ID and Role ID are required for assignment.'];
        }
    
        if ($this->userRoleModel->assign($userId, $roleId)) {
            return ['success' => true, 'message' => 'Role successfully assigned to user.'];
        } else {
            return ['success' => false, 'message' => 'Failed to assign role to user. Please check the provided IDs and try again.'];
        }
    }
}
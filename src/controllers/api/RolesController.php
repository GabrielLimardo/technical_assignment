<?php
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/Role.php';
require_once __DIR__ . '/../../models/UserRole.php';

class RolesController {

    private $roleModel;
    private $userRoleModel;


    public function __construct() {
        $db = new Database(); 
        $this->roleModel = new Role($db->getConnection());
        $this->userRoleModel = new UserRole($db->getConnection());
    }

    public function createRole() {
        try {
            $name = $_POST['name'] ?? null;
            if ($name === null) {
                throw new \Exception('Role name is required.');
            }
    
            if ($this->roleModel->create($name)) {
                return ['success' => true, 'message' => 'Role successfully created.'];
            } else {
                throw new \Exception('Failed to create role. Please try again.');
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function assignRoleToUser() {
        try {
            $userId = $_POST['userId'] ?? null;
            $roleId = $_POST['roleId'] ?? null;
    
            if ($userId === null || $roleId === null) {
                throw new \Exception('User ID and Role ID are required for assignment.');
            }
    
            if ($this->userRoleModel->assign($userId, $roleId)) {
                return ['success' => true, 'message' => 'Role successfully assigned to user.'];
            } else {
                throw new \Exception('Failed to assign role to user. Please check the provided IDs and try again.');
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }    
}
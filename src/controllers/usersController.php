<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';

class usersController {

    private $userModel;
    private $roleModel;

    public function __construct() {
        $db = new Database(); 
        $this->userModel = new User($db->getConnection());
        $this->roleModel = new Role($db->getConnection());
    }

    public function index() {
        try {
            $users = $this->userModel->getAllUsers();
            $roles = $this->roleModel->getAllRoles();
            require_once __DIR__ . '/../../resources/views/user_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }

    public function show($id) {
        try {
            $user = $this->userModel->getUserById($id);
            $roles = $this->roleModel->getAllRoles();
            
            require_once __DIR__ . '/../../resources/views/user_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
    
    public function edit() {
        try {
            $userId = $_POST['userId'];
            $newUsername = $_POST['newUsername'] ?? null;
            $newPassword = $_POST['newPassword'] ?? null;
            $newRole = $_POST['newRole'] ?? null;
    
            $this->userModel->editUser($userId, $newUsername, $newPassword, $newRole);
            $roles = $this->roleModel->getAllRoles();
            $users = $this->userModel->getAllUsers();
    
            require_once __DIR__ . '/../../resources/views/user_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
}

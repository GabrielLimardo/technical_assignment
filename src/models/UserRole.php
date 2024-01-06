<?php

class UserRole {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function assign($userId, $roleId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:userId, :roleId)");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':roleId', $roleId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

}

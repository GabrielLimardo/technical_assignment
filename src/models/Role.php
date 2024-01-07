<?php

class Role {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(string $name): bool {
        $stmt = $this->db->prepare("INSERT INTO roles (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    public function getAllRoles(): array {
        $stmt = $this->db->prepare("SELECT * FROM roles");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
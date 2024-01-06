<?php

class Role {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($name) {
        try {
            $stmt = $this->db->prepare("INSERT INTO roles (name) VALUES (:name)");
            $stmt->bindParam(':name', $name);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

}
<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

$database = new Database();
$pdo = $database->getConnection();

$migrationPath = __DIR__ . '/technical_assignment.sql';

try {
    $sql = file_get_contents($migrationPath);

    $pdo->exec($sql);

    var_dump("Migration completed successfully.\n");

} catch (PDOException $e) {
    var_dump("Error during migration: " . $e->getMessage());
}
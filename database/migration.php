<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

$database = new Database();
$pdo = $database->getConnection();

$migrationPath = __DIR__ . '/technical_assignment.sql';

try {
    $sql = file_get_contents($migrationPath);

    $pdo->exec($sql);

    return "Migration completed successfully.\n";

} catch (PDOException $e) {
    return "Error during migration: " . $e->getMessage();
}
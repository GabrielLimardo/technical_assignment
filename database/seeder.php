<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

$database = new Database();
$pdo = $database->getConnection();

$users = [
    ['id' => 1, 'username' => 'John', 'password' => '1234'],
    ['id' => 2, 'username' => 'Jane', 'password' => '1234'],
];

try {
    foreach ($users as $user) {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (id, username, password) VALUES (:id, :username, :password)");
        $stmt->bindParam(':id', $user['id']);
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindValue(':role_id', $user['id']);
        $stmt->execute();

        var_dump("User inserted: " . $user['username'] . "\n");
    }

    $transactions = [
        ['user_id' => 1, 'type' => 'income', 'amount' => 100.50, 'date' => '2024-01-01', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 75.00, 'date' => '2024-01-02', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 50.75, 'date' => '2024-01-03', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'expense', 'amount' => -25.25, 'date' => '2024-01-04', 'description' => 'Expense transaction'],
        
        ['user_id' => 2, 'type' => 'income', 'amount' => 120.00, 'date' => '2024-01-01', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'income', 'amount' => 90.25, 'date' => '2024-01-02', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -50.50, 'date' => '2024-01-03', 'description' => 'Expense transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -35.75, 'date' => '2024-01-04', 'description' => 'Expense transaction'],

    ];

    foreach ($transactions as $transaction) {
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, date, description) VALUES (:user_id, :type, :amount, :date, :description)");
        $stmt->execute($transaction);
        var_dump("Transaction inserted for user ID: " . $transaction['user_id'] . "\n");
    }

} catch (PDOException $e) {
    var_dump("Error inserting user: " . $e->getMessage());exit;
}

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
        $stmt->bindValue(':role_id', $user['id']);  // Alternar entre 1 y 2 para roles client y admin
        $stmt->execute();

        return "User inserted: " . $user['username'] . "\n";
    }

    $transactions = [
        ['user_id' => 1, 'type' => 'income', 'amount' => 100.50, 'date' => '2024-01-01', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 75.00, 'date' => '2024-01-02', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 50.75, 'date' => '2024-01-03', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'expense', 'amount' => -25.25, 'date' => '2024-01-04', 'description' => 'Expense transaction'],
        ['user_id' => 1, 'type' => 'expense', 'amount' => -40.00, 'date' => '2024-01-05', 'description' => 'Expense transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 30.50, 'date' => '2024-01-06', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'expense', 'amount' => -15.75, 'date' => '2024-01-07', 'description' => 'Expense transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 45.25, 'date' => '2024-01-08', 'description' => 'Income transaction'],
        ['user_id' => 1, 'type' => 'expense', 'amount' => -60.50, 'date' => '2024-01-09', 'description' => 'Expense transaction'],
        ['user_id' => 1, 'type' => 'income', 'amount' => 20.75, 'date' => '2024-01-10', 'description' => 'Income transaction'],
        
        ['user_id' => 2, 'type' => 'income', 'amount' => 120.00, 'date' => '2024-01-01', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'income', 'amount' => 90.25, 'date' => '2024-01-02', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -50.50, 'date' => '2024-01-03', 'description' => 'Expense transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -35.75, 'date' => '2024-01-04', 'description' => 'Expense transaction'],
        ['user_id' => 2, 'type' => 'income', 'amount' => 60.00, 'date' => '2024-01-05', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -25.25, 'date' => '2024-01-06', 'description' => 'Expense transaction'],
        ['user_id' => 2, 'type' => 'income', 'amount' => 70.50, 'date' => '2024-01-07', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -45.75, 'date' => '2024-01-08', 'description' => 'Expense transaction'],
        ['user_id' => 2, 'type' => 'income', 'amount' => 85.25, 'date' => '2024-01-09', 'description' => 'Income transaction'],
        ['user_id' => 2, 'type' => 'expense', 'amount' => -55.50, 'date' => '2024-01-10', 'description' => 'Expense transaction'],
    ];

    foreach ($transactions as $transaction) {
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, date, description) VALUES (:user_id, :type, :amount, :date, :description)");
        $stmt->execute($transaction);
        return "Transaction inserted for user ID: " . $transaction['user_id'] . "\n";
    }

} catch (PDOException $e) {
    return "Error inserting user: " . $e->getMessage();
}

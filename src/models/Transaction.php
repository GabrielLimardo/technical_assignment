<?php

class Transaction {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllTransactions(): array {
        $stmt = $this->db->prepare("SELECT * FROM transactions");
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction['amount'];
        }

        $result = [
            'transactions' => $transactions,
            'balance' => $balance
        ];
        
        return $result;
    }

    public function getTransactionsByDate(string  $dateFrom,string  $dateTo): array {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE date BETWEEN :dateFrom AND :dateTo");
        $stmt->bindParam(':dateFrom', $dateFrom);
        $stmt->bindParam(':dateTo', $dateTo);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction['amount'];
        }

        $result = [
            'transactions' => $transactions,
            'balance' => $balance
        ];

        return $result;
    }

    public function createTransaction(int $userId, string $type, float $amount, string $date, ?string $description = null): bool {
        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, type, amount, date, description) VALUES (:userId, :type, :amount, :date, :description)");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function getUserIdFromUsername(string $username): ?int {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null; 
    }
}

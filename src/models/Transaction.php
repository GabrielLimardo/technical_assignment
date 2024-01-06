<?php

class Transaction {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllTransactions() {
        try {
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
        } catch (PDOException $e) {
            throw new \Exception("Error al obtener la transaction: " . $e->getMessage());
        }
    }

    public function getTransactionsByDate($dateFrom, $dateTo) {
        try {
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

        } catch (PDOException $e) {
            throw new \Exception("Error al obtener la transaction: " . $e->getMessage());
        }
    }

    public function createTransaction($userId, $type, $amount, $date, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO transactions (user_id, type, amount, date, description) VALUES (:userId, :type, :amount, :date, :description)");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':description', $description);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception("Error al obtener la transaction: " . $e->getMessage());
        }
    }

    public function getUserIdFromUsername($username) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null; 
        } catch (PDOException $e) {
            throw new \Exception("Error al obtener la transaction: " . $e->getMessage());
        }
    }
}

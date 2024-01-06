<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';

class TransactionController {
    private $transactionModel;

    public function __construct() {
        $db = new Database(); 
        $this->transactionModel = new Transaction($db->getConnection());
    }

   public function createNewTransaction() {
        $username = $_POST['username'] ?? null;
        $type = $_POST['type'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $date = $_POST['date'] ?? null;
        $description = $_POST['description'] ?? null;
        $userId = $this->transactionModel->getUserIdFromUsername($username);

        if (!$userId || !$type || !$amount || !$date) {
            return ['success' => false, 'message' => 'Required parameters are missing for creating a new transaction.'];
        }

        if ($this->transactionModel->createTransaction($userId, $type, $amount, $date, $description)) {
            return ['success' => true, 'message' => 'Transaction successfully created.'];
        } else {
            return ['success' => false, 'message' => 'Failed to create transaction. Please try again.'];
        }
    }

    public function getAllTransactions() {
        $transactions = $this->transactionModel->getAllTransactions();
        
        if ($transactions) {
            return ['success' => true, 'data' => $transactions];
        } else {
            return ['success' => false, 'message' => 'Failed to retrieve transactions.'];
        }
    }

    public function getTransactionsByDate() {
        $dateFrom = $_POST['dateFrom'] ?? null;
        $dateTo = $_POST['dateTo'] ?? null;

        if (!$dateFrom || !$dateTo) {
            return ['success' => false, 'message' => 'Both start and end dates are required to fetch transactions by date range.'];
        }

        $transactions = $this->transactionModel->getTransactionsByDate($dateFrom, $dateTo);
        
        if ($transactions) {
            return ['success' => true, 'data' => $transactions];
        } else {
            return ['success' => false, 'message' => 'No transactions found for the specified date range.'];
        }
    }
}

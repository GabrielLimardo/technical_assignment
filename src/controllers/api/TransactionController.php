<?php

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../models/Transaction.php';

class TransactionController {
    private $transactionModel;

    public function __construct() {
        $db = new Database(); 
        $this->transactionModel = new Transaction($db->getConnection());
    }

    public function createNewTransaction() {
        try {
            $username = $_POST['username'] ?? null;
            $type = $_POST['type'] ?? null;
            $amount = $_POST['amount'] ?? null;
            $date = $_POST['date'] ?? null;
            $description = $_POST['description'] ?? null;
            $userId = $this->transactionModel->getUserIdFromUsername($username);
    
            if (!$userId || !$type || !$amount || !$date) {
                throw new \Exception('Required parameters are missing for creating a new transaction.');
            }
    
            if ($this->transactionModel->createTransaction($userId, $type, $amount, $date, $description)) {
                return ['success' => true, 'message' => 'Transaction successfully created.'];
            } else {
                throw new \Exception('Failed to create transaction. Please try again.');
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getAllTransactions() {
        try {
            $transactions = $this->transactionModel->getAllTransactions();
            
            if ($transactions) {
                return ['success' => true, 'data' => $transactions];
            } else {
                throw new \Exception('Failed to retrieve transactions.');
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getTransactionsByDate() {
        try {
            $dateFrom = $_POST['dateFrom'] ?? null;
            $dateTo = $_POST['dateTo'] ?? null;
    
            if (!$dateFrom || !$dateTo) {
                throw new \Exception('Both start and end dates are required to fetch transactions by date range.');
            }
    
            $transactions = $this->transactionModel->getTransactionsByDate($dateFrom, $dateTo);
            
            if ($transactions) {
                return ['success' => true, 'data' => $transactions];
            } else {
                throw new \Exception('No transactions found for the specified date range.');
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

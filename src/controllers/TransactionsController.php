<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';

class TransactionsController {
    private $transactionModel;

    public function __construct() {
        $db = new Database(); 
        $this->transactionModel = new Transaction($db->getConnection());
    }

    public function index() {
        require_once __DIR__ . '/../../resources/views/transaction_view.php';
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
            $transactions = $this->transactionModel->getAllTransactions();
            require_once __DIR__ . '/../../resources/views/index_view.php';
        } else {
            return ['success' => false, 'message' => 'Failed to create transaction. Please try again.'];
        }
   }
}

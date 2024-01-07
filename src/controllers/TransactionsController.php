<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../services/TransactionService.php';

class TransactionsController {
    private $transactionModel;
    protected $transactionService;


    public function __construct() {
        $db = new Database(); 
        $this->transactionModel = new Transaction($db->getConnection());
        $this->transactionService = new TransactionService($this->transactionModel);

    }

    public function index() {
        try {
            require_once __DIR__ . '/../../resources/views/transaction_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
    
    public function createNewTransaction() {
        try {
            if (isset($_SESSION['transaction_attempts']) && $_SESSION['transaction_attempts'] > 5) {
                throw new \Exception('Too many failed attempts. Please try again later.');
            }
        
            $username = $_POST['username'] ?? null;
            $type = $_POST['type'] ?? null;
            $amount = $_POST['amount'] ?? null;
            $date = $_POST['date'] ?? null;
            $description = $_POST['description'] ?? null;

            if ($this->transactionService->createNewTransaction($username, $type, $amount, $date, $description)) {
                $transactions = $this->transactionModel->getAllTransactions();
                require_once __DIR__ . '/../../resources/views/index_view.php';
                unset($_SESSION['login_attempts']);
            } else {
                throw new \Exception('Failed to create transaction. Please try again.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $_SESSION['transaction_attempts'] = $_SESSION['transaction_attempts'] ?? 0;
            $_SESSION['transaction_attempts']++;
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
}

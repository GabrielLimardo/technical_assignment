<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';

class indexController {

    private $transactionModel;

    public function __construct() {
        $db = new Database(); 
        $this->transactionModel = new Transaction($db->getConnection());
    }

    public function index() {
        try {
            $transactions = $this->transactionModel->getAllTransactions();
            require_once __DIR__ . '/../../resources/views/index_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
    
    public function filter() {
        try {
            $dateFrom = $_POST['dateFrom'] ?? null;
            $dateTo = $_POST['dateTo'] ?? null;
    
            if (!$dateFrom || !$dateTo) {
                throw new \Exception('Date range parameters are missing.');
            }
            
            $transactions = $this->transactionModel->getTransactionsByDate($dateFrom, $dateTo);
            require_once __DIR__ . '/../../resources/views/index_view.php';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../../resources/views/error_view.php';
        }
    }
}

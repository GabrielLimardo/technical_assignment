<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';

class usersController {

    private $transactionModel;

    public function __construct() {
        $db = new Database(); 
        $this->transactionModel = new Transaction($db->getConnection());
    }

    public function index() {
        $transactions = $this->transactionModel->getAllTransactions();
        require_once __DIR__ . '/../../resources/views/index.php';
    }

    public function filter() {

        $dateFrom = $_POST['dateFrom'] ?? null;
        $dateTo = $_POST['dateTo'] ?? null;
        
        $transactions = $this->transactionModel->getTransactionsByDate($dateFrom, $dateTo);

        require_once __DIR__ . '/../../resources/views/index.php';
    }
}

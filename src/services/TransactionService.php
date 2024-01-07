<?php

class TransactionService {
    
    protected $transactionModel;

    public function __construct($transactionModel) {
        $this->transactionModel = $transactionModel;
    }

    public function createNewTransaction(string $username, string $type, float $amount, string $date, ?string $description = null): bool {
        $userId = $this->transactionModel->getUserIdFromUsername($username);

        if (!$userId) {
            throw new \Exception('Username used is not correct.');
        }

        if (!$type || !$amount || !$date) {
            throw new \Exception('Required parameters are missing for creating a new transaction.');
        }

        if ($type === 'income') {
            $amount = abs($amount);
        } elseif ($type === 'expense') {
            $amount = -abs($amount);
        } else {
            throw new \Exception('Invalid transaction type.');
        }

        return $this->transactionModel->createTransaction($userId, $type, $amount, $date, $description);
    }

    public function handleDateFilter(string $dateFrom, string $dateTo): array {
        if (!$dateFrom || !$dateTo) {
            throw new \Exception('Date range parameters are missing.');
        }
        
        $transactions = $this->transactionModel->getTransactionsByDate($dateFrom, $dateTo);
        
        return $transactions;
    }
}

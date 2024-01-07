<?php

require_once __DIR__ . '/../../../src/models/Transaction.php';


use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private $pdo;
    private $transaction;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("
            CREATE TABLE transactions (
                id INTEGER PRIMARY KEY,
                user_id INTEGER,
                type TEXT,
                amount DECIMAL(10, 2),
                date DATE,
                description TEXT
            );
            
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                username TEXT
            );
        ");

        $this->transaction = new Transaction($this->pdo);
    }

    public function testGetAllTransactions(): void
    {
        $result = $this->transaction->getAllTransactions();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('transactions', $result);
        $this->assertArrayHasKey('balance', $result);
    }

    public function testGetTransactionsByDate(): void
    {
        $dateFrom = '2023-01-01';
        $dateTo = '2023-12-31';
        $result = $this->transaction->getTransactionsByDate($dateFrom, $dateTo);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('transactions', $result);
        $this->assertArrayHasKey('balance', $result);
    }

    public function testCreateTransaction(): void
    {
        $userId = 1;
        $type = 'credit';
        $amount = 100.50;
        $date = '2023-01-15';
        $description = 'Test transaction';

        $result = $this->transaction->createTransaction($userId, $type, $amount, $date, $description);
        $this->assertTrue($result);
    }

    public function testGetUserIdFromUsername(): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (id, username) VALUES (1, 'testuser')");
        $stmt->execute();

        $userId = $this->transaction->getUserIdFromUsername('testuser');
        $this->assertEquals(1, $userId);
    }
}

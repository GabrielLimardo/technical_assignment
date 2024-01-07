<?php

require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';


class Transaction {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllTransactions(): array {
        $stmt = $this->db->prepare("SELECT * FROM transactions LIMIT 10");
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $stmtTotal = $this->db->prepare("SELECT SUM(amount) as totalBalance FROM transactions");
        $stmtTotal->execute();
        $totalBalance = $stmtTotal->fetch(PDO::FETCH_ASSOC)['totalBalance'];
    
        $result = [
            'transactions' => $transactions,
            'balance' => $totalBalance
        ];
        
        return $result;
    }

    public function getTransactionsByDate(string  $dateFrom,string  $dateTo): array {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE date BETWEEN :dateFrom AND :dateTo LIMIT 10");
        $stmt->bindParam(':dateFrom', $dateFrom);
        $stmt->bindParam(':dateTo', $dateTo);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtTotal = $this->db->prepare("SELECT SUM(amount) as totalBalance FROM transactions WHERE date BETWEEN :dateFrom AND :dateTo");
        $stmtTotal->bindParam(':dateFrom', $dateFrom);
        $stmtTotal->bindParam(':dateTo', $dateTo);
        $stmtTotal->execute();
        $totalBalance = $stmtTotal->fetch(PDO::FETCH_ASSOC)['totalBalance'];

        $result = [
            'transactions' => $transactions,
            'balance' => $totalBalance
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

    public function generateBalancePdf() {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Balance Report');
        $pdf->SetHeaderData('', 0, 'Balance Report', '', [0, 64, 255], [0, 64, 128]);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
        $pdf->AddPage();

        $stmt = $this->db->prepare("SELECT * FROM transactions");
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $totalBalance = 0;
        $content = "<h1>Balance Report</h1>\n\n";
        $content .= "<br>";
    
        foreach ($transactions as $transaction) {
            $content .= "Type: " . ucfirst($transaction['type']) . "\n";
            $content .= "Amount: " . $transaction['amount'] . "\n";
            $content .= "Description: " . $transaction['description'] . "\n";
            $content .= "Date: " . $transaction['date'] . "\n";
            $content .= "<br>"; // Agrega un salto de línea después de cada transacción
    
            if ($transaction['type'] === 'income') {
                $totalBalance += $transaction['amount'];
            } elseif ($transaction['type'] === 'expense') {
                $totalBalance -= $transaction['amount'];
            }
        }
    
        $content .= "\nTotal Balance: $totalBalance\n\n"; // Agrega el balance total al final
    
        
        $pdf->writeHTML($content, true, false, true, false, '');
    
        $pdf->Output('balance_report.pdf', 'I');
    }    
}

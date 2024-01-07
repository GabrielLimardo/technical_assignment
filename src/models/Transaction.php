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
    
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $transaction) {
            $content .= "Type: " . ucfirst($transaction['type']) . "\n";
            $content .= "Amount: " . $transaction['amount'] . "\n";
            $content .= "Description: " . $transaction['description'] . "\n";
            $content .= "Date: " . $transaction['date'] . "\n";
            $content .= "<br>";

            if ($transaction['type'] === 'income') {
                $totalIncome += $transaction['amount'];
            } elseif ($transaction['type'] === 'expense') {
                $totalExpense += $transaction['amount'];
            }
        }

        $totalBalance = $totalIncome - $totalExpense;
            
        $content .= "\nTotal Balance: $totalBalance\n\n"; 
        $total = $totalIncome + abs($totalExpense);

        $income_percentage = round(($totalIncome / $total) * 100, 1);
        $expense_percentage = round((abs($totalExpense) / $total) * 100, 1);

        $html = '
        <table border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td width="' . $income_percentage . '%" bgcolor="#00FF00">Income (' . $income_percentage . '%) - Income Total: ' . $totalIncome . ' </td>  <!-- Verde -->
                <td width="' . $expense_percentage . '%" bgcolor="#FF0000">Expense (' . $expense_percentage . '%) - Expense Total:' . abs($totalExpense) . ' </td>  <!-- Rojo -->
            </tr>
        </table>
        ';
    
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->writeHTML($content, true, false, true, false, '');
    
        $pdf->Output('balance_report.pdf', 'I');
    }    
}

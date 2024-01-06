<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main View</title>
</head>
<body>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div style="margin-top: 20px;">
            <h2>Logged-in User Data:</h2>
            <p>User ID: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A'; ?></p>
            <p>Username: <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'N/A'; ?></p>
        </div>
        
        <a href="/technical_assignment/logout" style="margin-left: 20px;">Log out</a>

        <a href="/technical_assignment/users" style="margin-left: 20px;">Users</a>


    <?php endif; ?>

    <?php if (!isset($_SESSION['user_id'])): ?>

        <a href="/technical_assignment/login" style="margin-left: 20px;">Login</a>

    <?php endif; ?>
    
    <a href="/technical_assignment/transaction" style="margin-left: 20px;">Create transaction</a>


    <h1>Transactions Table</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions['transactions'] as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['type']; ?></td>
                    <td><?php echo $transaction['amount']; ?></td>
                    <td><?php echo $transaction['date']; ?></td>
                    <td><?php echo $transaction['description']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Balance: <?php echo $transactions['balance']; ?></p>

    
    <div style="margin-top: 20px;">
        <h2>Filter Transactions by Date</h2>
        <form action="/technical_assignment/filter" method="post">
            <label for="dateFrom">From:</label>
            <input type="date" id="dateFrom" name="dateFrom" required><br><br>
            
            <label for="dateTo">To:</label>
            <input type="date" id="dateTo" name="dateTo" required><br><br>
            
            <input type="submit" value="Filter">
            <a href="/technical_assignment/home" style="margin-left: 20px;">Clear Filter</a>
        </form>
    </div>
    

</body>
</html>

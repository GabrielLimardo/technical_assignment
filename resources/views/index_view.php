<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main View</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        input.button {
            padding: 12px 24px; /* Aumentamos el padding para que sea un poco más grande */
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            display: inline-block;
            transition: background-color 0.3s; /* Transición suave para el cambio de color */
        }
        input.button:hover {
            background-color: #0056b3;
        }
        input.button:hover {
            background-color: #0056b3;
        }
        a.button {
            padding: 12px 24px; /* Aumentamos el padding para que sea un poco más grande */
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            display: inline-block;
            transition: background-color 0.3s; /* Transición suave para el cambio de color */
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
    <div>
        <h2>Logged-in User Data:</h2>
        <p>User ID: <?php echo htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Username: <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    
    <a href="/technical_assignment/logout" class="button">Log out</a>
    <a href="/technical_assignment/users" class="button">Users</a>

<?php else: ?>

    <a href="/technical_assignment/login" class="button">Login</a>

<?php endif; ?>

<a href="/technical_assignment/transaction" class="button">Create Transaction</a>


<h1>Transactions Table</h1>

<table>
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
                <td><?php echo htmlspecialchars($transaction['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($transaction['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($transaction['amount'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($transaction['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($transaction['description'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Final Balance: <?php echo htmlspecialchars($transactions['balance'], ENT_QUOTES, 'UTF-8'); ?></h3>

<a href="/technical_assignment/home" class="button clear-button">Clear Filter</a>

<div>
    <h2>Filter Transactions by Date</h2>
    <form action="/technical_assignment/filter" method="post">
        <label for="dateFrom">From:</label>
        <input type="date" id="dateFrom" name="dateFrom" required><br><br>
        
        <label for="dateTo">To:</label>
        <input type="date" id="dateTo" name="dateTo" required><br><br>
        
        <input type="submit" value="Filter" class="button">
        <br>
    </form>
</div>


</body>
</html>

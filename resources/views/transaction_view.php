<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Transaction</title>
</head>
<body>

<h2>Create New Transaction</h2>

<form action="/technical_assignment/transaction_form" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="type">Type:</label>
    <select id="type" name="type" required>
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select><br><br>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" step="0.01" required><br><br>

    <label for="date">Date:</label>
    <input type="date" id="date" name="date" required><br><br>

    <label for="description">Description (Optional):</label><br>
    <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>

    <input type="submit" value="Create Transaction">
</form>

</body>
</html>

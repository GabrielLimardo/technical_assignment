<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        button {
            padding: 10px 20px;
            margin: 10px 0;
        }
        #edit-form {
            display: none;
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 15px;
        }
        form label {
            display: block;
            margin-top: 10px;
        }
        form input, form select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <h1>User List</h1>

    <button onclick="window.location.href='/technical_assignment/home'">Go Back to Home</button>

    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?>
                <button onclick="showEditForm(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?>')">Edit</button>
            </li>
        <?php endforeach; ?>
    </ul>

    <div id="edit-form">
        <h2>Edit User</h2>
        <form action="/technical_assignment/users_form" method="post">
            <input type="hidden" id="edit-user-id" name="userId">
            
            <label for="edit-username">Username:</label>
            <input type="text" id="edit-username" name="newUsername">
            
            <label for="edit-password">Password:</label>
            <input type="password" id="edit-password" name="newPassword">
            
            <label for="edit-role">Role:</label>
            <select id="edit-role" name="newRole">
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo htmlspecialchars($role['id'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($role['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
            </select>
            
            <input type="submit" value="Save Changes">
        </form>
    </div>

    <script>
        function showEditForm(userId, username, role) {
            document.getElementById('edit-user-id').value = userId;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-role').value = role;
            document.getElementById('edit-form').style.display = 'block';
        }
    </script>

</body>
</html>

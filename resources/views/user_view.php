<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
</head>
<body>

    <h1>User List</h1>

    <button onclick="window.location.href='/technical_assignment/home'">Go Back to Home</button>

    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo $user['username']; ?> - <?php echo $user['role']; ?>
                <!-- Button to edit this user -->
                <button onclick="showEditForm(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>', '<?php echo $user['role']; ?>')">Edit</button>
            </li>
        <?php endforeach; ?>
    </ul>

    <div id="edit-form" style="display:none;">
    <h2>Edit User</h2>
        <form action="/technical_assignment/users_form" method="post">
            <input type="hidden" id="edit-user-id" name="userId">
            
            <label for="edit-username">Username:</label>
            <input type="text" id="edit-username" name="newUsername">
            
            <label for="edit-password">Password:</label>
            <input type="password" id="edit-password" name="newPassword">
            
            <label for="edit-role">Role:</label>
            <select id="edit-role" name="newRole">
                <!-- Role options will be inserted here -->
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
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

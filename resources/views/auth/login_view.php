<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Simple styles to center the form */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <form action="/login" method="post">
        <h2>Login</h2>
        
        <!-- Username field -->
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <!-- Password field -->
        <div style="margin-top: 10px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <!-- Submit button -->
        <div style="margin-top: 20px;">
            <input type="submit" value="Login">
        </div>
        
        <!-- Link to registration page -->
        <div style="margin-top: 20px;">
            Don't have an account? <a href="/register">Register</a>
        </div>
    </form>
</body>
</html>

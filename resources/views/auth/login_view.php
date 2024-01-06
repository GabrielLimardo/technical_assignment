<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
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
    <form action="/technical_assignment/login_form" method="post">
        <h2>Login</h2>
        
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div style="margin-top: 10px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div style="margin-top: 20px;">
            <input type="submit" value="Login">
        </div>
        
    </form>


    <a href="/technical_assignment/home" style="margin-left: 20px;">Home</a>

</body>
</html>

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
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        
        form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        label, input[type="text"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            /* border: 1px solid #ccc; */
        }
        
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        
        a {
            margin-top: 20px;
            display: inline-block;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        a:hover {
            color: #0056b3;
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
    </style>
</head>
<body>
    <div>
        <form action="/technical_assignment/login_form" method="post">
            <h2>Login</h2>
            
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
    <div>

    <br>
    <a href="/technical_assignment/home">Home</a>

</body>
</html>

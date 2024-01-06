<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .error-title {
            color: #dc3545;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .error-message {
            color: #dc3545;
            font-size: 18px;
            margin-bottom: 30px;
        }
        .home-button {
            margin-top: 20px;
        }
        .home-button button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .home-button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-title">¡Ups! Ha ocurrido un error.</div>
        <div class="error-message">
            <?php echo $errorMessage; ?>
        </div>
        <div class="home-button">
            <button onclick="goToHome()">Volver al Home</button>
        </div>
    </div>

    <script>
        function goToHome() {
            window.location.href = '/technical_assignment/home';
        }
    </script>
</body>
</html>

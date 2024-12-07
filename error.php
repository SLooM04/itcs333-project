<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .error-container img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .error-container h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .error-container p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .error-container a {
            text-decoration: none;
            background-color: #f5c6cb;
            color: #721c24;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        .error-container a:hover {
            background-color: #f1b0b7;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <img src="https://www.freeiconspng.com/thumbs/error-icon/error-icon-4.png" alt="Error">
        <h1>Oops! Something went wrong.</h1>
        <p><?php echo htmlspecialchars($_GET['msg'] ?? 'An unknown error occurred.'); ?></p>
        <a href="javascript:history.back()">Go Back</a>
    </div>
</body>
</html>

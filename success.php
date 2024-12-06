<?php
// Start the session
session_start();

// Check if the registration was successful
if (isset($_SESSION['registration_success'])) {
    // Unset the success message to avoid showing it again on refresh
    unset($_SESSION['registration_success_success']);
    ?>
    <!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
    <meta http-equiv='refresh' content='6;url=combined_login.php'> <!-- Redirect to login page after 6 seconds -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9); /* Light green gradient */
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1); /* Transparent white box */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.5s ease-in-out;
        }
        .container h1 {
            font-size: 2.5em;
            margin: 0 0 10px;
            color: #388e3c;
        }
        .container p {
            font-size: 1.2em;
            margin: 10px 0 20px;
            color: #1b5e20;
        }
        .loader {
            border: 5px solid #e8f5e9; /* Light green background */
            border-top: 5px solid #388e3c; /* Dark green for spinning animation */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Success!</h1>
        <p>Your registration was successful. You will be redirected to the login page shortly.</p>
        <div class='loader'></div>
    </div>
</body>
</html>
<?php
} else {
    // Redirect to the registration page if accessed incorrectly
    header("Location: account_type.php");
    exit();
}

?>
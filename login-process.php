<?php
session_start();

// In a real-world scenario, you would fetch user data from a database
// For this example, we'll use the in-memory storage from the registration process
if (!isset($_SESSION["users"])) {
    $_SESSION["users"] = [];
}

$users = $_SESSION["users"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (isset($users[$email])) {
        // Retrieve the hashed password and username
        $user_data = $users[$email];
        $hashed_password = $user_data['password'];
        $username = $user_data['username'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Authentication successful
            $_SESSION['user_id'] = $email; // Use email as the user ID (or replace with database ID)
            $_SESSION['username'] = $username;
            $_SESSION["login_success"] = "Welcome, $username!";
            sleep(seconds: 2);
            header("Location: test.php");
            exit();
        }
    }
    
    // Authentication failed
    $_SESSION['login_error'] = "Invalid username or password";
    sleep(2);
    header(header: "Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}


?>
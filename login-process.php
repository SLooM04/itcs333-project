<?php
session_start();

// In a real-world scenario, you would fetch user data from a database
// For this example, we'll use the in-memory storage from the registration process
if (!isset($_SESSION["users"])) {
    $_SESSION["users"] = [];
}

$users = $_SESSION["users"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (isset($users[$email])) {
        // Verify the password
        if (password_verify($password, $users[$email])) {
            // Authentication successful
            $_SESSION['user_id'] = $email; // In reality, this would be a unique user ID
            $_SESSION['username'] = $email;
            echo $_SESSION["registration_success"];
            sleep(3);
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
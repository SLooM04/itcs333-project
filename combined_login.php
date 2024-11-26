<?php
session_start(); // Ensure session is started at the top of the file
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
  <style>
  /* General styling */
  body {
    font-family: 'Roboto', sans-serif;
    background-color: #eef2f3;
    margin: 0;
    padding: 0;
    color: #333;
  }

  main {
    display: grid;
    place-items: center;
    min-height: 100vh;
    padding: 20px;
  }

  /* Form container */
  .form-container {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 20px;
    max-width: 400px;
    width: 100%;
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  /* Heading */
  h1 {
    font-size: 2em;
    color: #0061f2;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
  }

  /* Form groups */
  .form-group {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 10px;
  }

  .form-group label {
    font-size: 1.1em;
    color: #555;
  }

  .form-group input {
    padding: 15px;
    font-size: 1em;
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    color: #333;
    transition: all 0.3s;
  }

  .form-group input:focus {
    border-color: #0061f2;
    background-color: #f1faff;
    outline: none;
  }

  /* Submit Button */
  button {
    padding: 15px;
    font-size: 1.2em;
    background-color: #0061f2;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  button:hover {
    background-color: #004bb5;
  }

  .login-section p{
    color: #333;
  }

  .login-section a{
    color:#0061f2;
   font-weight: bold;
  }
 
  /* Error/Sucess messages */
  .error-message,
  .success-message {
    text-align: center;
    padding: 10px;
    border-radius: 5px;
  }

  .error-message {
    background-color: #ffe6e6;
    color: red;
    border: 1px solid red;
  }

  .success-message {
    background-color: #e6ffe6;
    color: green;
    border: 1px solid green;
  }

  /* Footer styling */
  footer {
    margin-top: 30px;
    text-align: center;
    font-size: 0.9em;
    color: #888;
  }

  footer ul {
    list-style: none;
    padding: 0;
    margin-top: 10px;
  }

  footer ul li {
    display: inline-block;
    margin-right: 15px;
  }

  footer ul li a {
    color: #0061f2;
    text-decoration: none;
  }

  footer ul li a:hover {
    text-decoration: underline;
  }

footer p {
  color: #333;
  }
</style>
</head>
<body>
  <main class="container">
    <!-- Display Error Message -->
    <?php
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['login_error']) . "</p>";
        unset($_SESSION['login_error']); // Clear the message after displaying it
    }
    if (isset($_SESSION['login_success'])) {
        echo "<p style='color: green;'>" . htmlspecialchars($_SESSION['login_success']) . "</p>";
        unset($_SESSION['login_success']);
    }
    ?>

    <!-- Login Form -->
    <section class="login-section">
      <h1>Login</h1>
      <form action="combined-login-process.php" method="POST" id="login-form" class="login-form">
        <!-- Email -->
        <div class="form-group" id="email-container">
          <label for="login-email">UoB Email</label>
          <input type="email" id="login-email" name="email" placeholder="Enter your email"  required>
        </div>

        <!-- Password -->
        <div class="form-group" id="password-container">
          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
        </div>

        <!-- Submit Button -->
        <div id="submit-container">
          <button type="submit" class="contrast">Login</button>
        </div>
      </form>
      <p>Don't have an account? <a href="account_type.php">Register here</a></p>
    </section>
  </main>
  <!-- Footer Section -->
  <footer class="container">
    <hr>
    <p>&copy; <?php echo date("Y"); ?> ITCS333 Project | All rights reserved.</p>
    <ul>
      <li><a href="#privacy-policy">Privacy Policy</a></li>
      <li><a href="#terms-of-service">Terms of Service</a></li>
      <li><a href="#contact">Contact Us</a></li>
    </ul>
  </footer>
</body>
</html>
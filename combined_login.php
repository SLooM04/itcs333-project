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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" >
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

  /* Footer styles */
  footer {
            color: white;
            background: linear-gradient(1deg, #024ba9, #96c3ff);  
            text-align: center;
            padding: 1rem 1rem;
            margin-top: 0rem;
            font-size: 0.9rem;
            z-index: 1;
        }

        footer .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            z-index: 1;
        }

        footer .footer-section {
            flex: 1 1 200px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: left;
            z-index: 1;
        }

        footer .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #ffffff;
            font-weight: 600;
        }

        footer .footer-section ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        footer .footer-section ul li a:hover {
            text-decoration: underline;
        }
        /* Responsive design for the footer */
        @media (max-width: 768px) {
            footer .footer-container {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-section {
                margin-bottom: 1.5rem; 
                text-align: center;
            }

            footer .footer-section ul li {
                margin: 0.2rem 0;
            }}

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
      <div style="position: relative;">
        <input type="password" id="login-password" name="password" placeholder="Enter your password" required style="width: 100%; padding-right: 40px;">
        <span id="toggle-password" style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer; color: #555;">
          <i class="fa-solid fa-eye"></i>
        </span>
      </div>
    </div>

    <!-- Include JavaScript to toggle visibility -->
    <script>
      document.getElementById("toggle-password").addEventListener("click", function() {
        const passwordField = document.getElementById("login-password");
        const icon = this.querySelector("i");
        const isPassword = passwordField.getAttribute("type") === "password";

        // Toggle input type
        passwordField.setAttribute("type", isPassword ? "text" : "password");

        // Toggle icon
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
      });
    </script>

        <!-- Submit Button -->
        <div id="submit-container">
          <button type="submit" class="contrast">Login</button>
        </div>
      </form>
      <p>Don't have an account? <a href="account_type.php">Register here</a></p>
    </section>
  </main>
  <!-- Footer -->
  <footer>
        <div class="footer-container">
            <!-- University Info -->
            <div class="footer-section">
                <h3>University Info</h3>
                <ul>
                    <li><a href="https://www.uob.edu.bh/about/our-leadership/">About Us</a></li>
                    <li><a href="https://www.uob.edu.bh/locations">Campus Locations</a></li>
                    <li><a href="#events">Upcoming Events</a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="https://www.uob.edu.bh/admission-requirements">Join UOB</a></li>
                    <li><a href="https://www.uob.edu.bh/deanship-of-graduate-studies-scientific-research">Research</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li>Email: <a href="mailto:info@university.com">info@university.com</a></li>
                    <li>Phone: +123 456 789</li>
                    <li>Address: Sakhir – Kingdom of Bahrain <br>1017 Road 5418 <br>Zallaq 1054</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p style="color:white;">&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy" style="color : white;">Privacy Policy | </a>
                <a href="https://www.uob.edu.bh/terms-and-conditions" style="color : white;">Terms of Service</a>
            </p>
        </div>
    </footer>
</body>
</html>
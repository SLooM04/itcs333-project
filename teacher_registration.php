<?php
session_start();
require 'db.php'; // Ensure you have a separate file for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $department = $_POST['department'];
    $mobile = trim($_POST['mobile']);

    // Define validation patterns
    $emailRegex = "/^[a-zA-Z0-9]+@uob\.edu\.bh$/";
    $passRegex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    $mobileRegex = "/^\+?[0-9]{7,15}$/"; // Adjust as per your mobile number format

    // Initialize an array to store error messages
    $errors = [];

    // Validate First Name
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }

    // Validate Last Name
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }

    // Validate Email
    if (!preg_match($emailRegex, $email)) {
        $errors[] = "Invalid email format. Please use a valid University of Bahrain email.";
    }

    // Validate Username
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    // Validate Password
    if (!preg_match($passRegex, $password)) {
        $errors[] = "Password must be at least 8 characters long, include uppercase and lowercase letters, a number, and a special character.";
    }

    // Confirm Password
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Validate Department
    $validDepartments = ["Information Systems", "Computer Science", "Computer Engineering"];
    if (!in_array($department, $validDepartments)) {
        $errors[] = "Please select a valid department.";
    }

    // Validate Mobile Number
    if (!preg_match($mobileRegex, $mobile)) {
        $errors[] = "Invalid mobile number format.";
    }

    // If there are no validation errors, proceed to register the user
    if (empty($errors)) {
        try {
            // Check if email or username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM teachers WHERE email = :email OR username = :username");
            $stmt->execute([':email' => $email, ':username' => $username]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Email or Username already exists. Please choose another.";
            } else {
                // Hash the password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare the INSERT statement
                $stmt = $pdo->prepare("
                    INSERT INTO teachers (first_name, last_name, email, username, password, department, mobile)
                    VALUES (:first_name, :last_name, :email, :username, :password, :department, :mobile)
                ");

                // Execute the statement with bound parameters
                $stmt->execute([
                    ':first_name' => $firstName,
                    ':last_name' => $lastName,
                    ':email' => $email,
                    ':username' => $username,
                    ':password' => $hashed_password,
                    ':department' => $department,
                    ':mobile' => $mobile,
                ]);

                // Set success message and redirect to login page
                $_SESSION['registration_success'] = "Registration successful. You can now log in.";
                header("Location: success.php");
                exit();
            }
        } catch (PDOException $e) {
            // Handle database errors
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

    // If there are errors, store them in the session and redirect back to the registration form
    if (!empty($errors)) {
        $_SESSION['registration_error'] = implode("<br>", $errors);
        echo "<p class = invalid>" . $_SESSION['registration_error'] . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }

        main {
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 3em;
            color: #0061f2;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        fieldset {
            border: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        legend {
            font-size: 1.6em;
            color: #0061f2;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            margin-right: 20px;
        }

        .form-group:last-child {
            margin-right: 0;
        }

        .form-group label {
            font-size: 1.1em;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            font-size: 1.2em;
            border: 2px solid #ddd;
            border-radius: 8px;
            width: 100%;
            background-color: #f9f9f9;
            color: #333;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #0061f2;
            background-color: #f1faff;
            outline: none;
        }

        button {
            padding: 18px;
            font-size: 1.3em;
            font-weight: bold;
            font-family: 'Roboto', sans-serif;
            background-color: #0061f2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #004bb5;
        }

        /* Ripple Effect */
    button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 20%;
        transition: transform 0.3s ease, opacity 0.6s ease;
        opacity: 0;
        pointer-events: none;
    }

    button:active::before {
        transform: translate(-40%, -50%) scale(1);
        opacity: 011;
    }
            /* Footer styles */
        footer {
            color: white;
            background-color: #1a73e8;
            text-align: center;
            padding: 1rem 1rem;
            margin-top: 9rem;
            font-size: 0.9rem;
        }

        footer .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        footer .footer-section {
            flex: 1 1 200px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: left;
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
            }
        }        p.already{
    color: #333;
    text-align: center;
        }

       p.already a{
    color:#0061f2;
   font-weight: bold;
        }

        p.invalid {
    background-color: #ffe6e6;
    color: red;
    border: 1px solid red;
  }

        p.success-message {
    background-color: #e6ffe6;
    color: green;
    border: 1px solid green;
  }
    </style>
    </style>
</head>
<body>
    <main class="container">
    <div class="form-container">
    <h1>Registration</h1>
        <form action="teacher_registration.php" method="POST">
            <fieldset>
                <legend class="form-legend"></legend>

                <!-- First Name & Last Name -->
                <div class="form-row">
                    <div id="registration-first-name" class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                    </div>
                    <div id="registration-last-name" class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
                    </div>
                </div>

                <!-- Email -->
                <div id="registration-email" class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <!-- Username -->
                <div id="registration-username" class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required>
                </div>

                <!-- Password Field -->
                <div class="form-row" id="password-container">
                <div id="registration-password" class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" placeholder="Create a password"  required />
                        <span id="toggle-password" style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer; color: #555;">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>
                

                <!-- Confirm Password Field -->
                <div class="form-group" id="confirm-password-container">
                    <label for="confirm-password">Confirm Password</label>
                    <div style="position: relative;">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required />
                        <span id="toggle-confirm-password" style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer; color: #555;">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                    </div>
                </div>

                <script>
                    // Toggle visibility function
                    function toggleVisibility(toggleId, inputId) {
                        const toggleButton = document.getElementById(toggleId);
                        const passwordField = document.getElementById(inputId);
                        const icon = toggleButton.querySelector("i");
                        const isPassword = passwordField.getAttribute("type") === "password";

                        // Toggle input type
                        passwordField.setAttribute("type", isPassword ? "text" : "password");

                        // Toggle icon
                        icon.classList.toggle("fa-eye");
                        icon.classList.toggle("fa-eye-slash");
                    }

                    // Event listeners for password toggle buttons
                    document.getElementById("toggle-password").addEventListener("click", function () {
                        toggleVisibility("toggle-password", "password");
                    });

                    document.getElementById("toggle-confirm-password").addEventListener("click", function () {
                        toggleVisibility("toggle-confirm-password", "confirm-password");
                    });
                </script>

                <!-- Department -->
                <div id="registration-department" class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="Information Systems">Information Systems</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                    </select>
                </div>

                <!-- Mobile -->
                <div id="registration-mobile" class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="tel" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
                </div>

                <div id="form-submit" class="form-group">
        <button type="submit" id="submit" class="contrast">Register as a Teacher</button>
      </div>
            </fieldset>
        </form>
        <p class = "already">Already have an account? <a href="combined_login.php">Login here</a></p>
  </div>
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

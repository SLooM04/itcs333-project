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
    $confirmPassword = $_POST['confirm_password'];
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
                header("Location: teacher-login.php");
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
        p.already{
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

                <!-- Password & Confirm Password -->
                <div class="form-row">
                    <div id="registration-password" class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div id="registration-confirm-password" class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                    </div>
                </div>

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
        <p class = "already">Already have an account? <a href="teacher-login.php">Login here</a></p>
  </div>
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

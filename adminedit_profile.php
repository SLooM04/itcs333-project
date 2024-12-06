<?php
session_start();
require 'db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("You are not logged in or authorized to edit the admin profile.");
}

$userId = $_SESSION['user_id'];

// Fetch admin details
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$userId]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin not found.");
}

// Handle form submission to update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = $admin['email']; // Preserve the current email from the database
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $errors = [];

    // Validate unique email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE email = ? AND id != ?");
    $stmt->execute([$email, $userId]);
    $emailExists = $stmt->fetchColumn() > 0;

    if ($emailExists) {
        $errors[] = "This email is already in use.";
    }

    // Validate unique username
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ? AND id != ?");
    $stmt->execute([$username, $userId]);
    $usernameExists = $stmt->fetchColumn() > 0;

    if ($usernameExists) {
        $errors[] = "This username is already in use.";
    }

    if (!empty($password) && $password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $admin['password'];

        // Update admin profile
        $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $hashedPassword, $userId]);

        $_SESSION['profile_update_success'] = "Profile updated successfully!";
        header("Location: adminprofile.php");
        exit();
    } else {
        $_SESSION['profile_update_error'] = implode("<br>", $errors);
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,600');
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: linear-gradient(#1a73e8, #004db3);
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin: 0px;
        }
        html {
            font-family: 'Montserrat', sans-serif;
            background-image: linear-gradient(#1a73e8, #004db3);
            background-repeat: no-repeat;
            background-size: cover;
            height: 180%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 35px;
            margin: 0px;
        }

        .edit-profile-container {
            background-color: #f5fafc;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding-right: 9%;
        }

        .form-group {
            text-align: left;
            margin-top: 20px;
        }

        h1{ color: #0458b5 ;}

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 2px solid #d1dbe6;
            margin-top: 5px;
        }

        .submit-btn {
            display: inline-block;
            padding: 12px 190px;
            background-color: #618bb8;
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: bold;
        }

        .profile-img-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-img-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        .delete-icon {
            position: absolute;
            top: 5px;
            right: 150px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            border: solid red;
            padding: 5px;
            cursor: pointer;
        }

        .delete-icon img {
            width: 20px;
            height: 20px;
        }

        .change-avatar-text {
            margin-top: 10px;
            font-size: 14px;
            color: #396391;
            cursor: pointer;
            font-weight: bold;
            display: block;
            text-align: center;
        }

        .delete-icon:hover {
            background-color: rgba(255, 200, 200, 1);
        }

        .error-message {
            color: #e74c3c;
        }

        @media (max-width: 768px) {
            .submit-btn {
                padding: 12px 100px;
            }
            .profile-img-container img {
                width: 120px;
                height: 120px;
            }
            .delete-icon {
            position: absolute;
            top: 5px;
            right: 100px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            border: solid red;
            padding: 5px;
            cursor: pointer;
            }  
            .delete-icon img {
             width: 16px; 
              height: 16px; 
               }
        }

        @media (max-width: 480px) {
            body {
                padding: 0px;
                height: 100%;
            }
            
            html {
                padding: 25px;
                height: 105%;
            }
            .edit-profile-container {
                padding: 20px;
                width: 100%;
                max-width: 100%;
            }
            .submit-btn {
                padding: 12px 50px;
            }
            .profile-img-container img {
                width: 100px;
                height: 100px;
            }
            .delete-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            border: solid red;
            padding: 5px;
            cursor: pointer;
            }
            .form-group input {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="edit-profile-container">
        <h1>Edit Admin Profile</h1>
        <?php if (isset($_SESSION['profile_update_error'])): ?>
            <p class="error-message"><?= $_SESSION['profile_update_error']; ?></p>
            <?php unset($_SESSION['profile_update_error']); ?>
        <?php endif; ?>

        <form action="adminedit_profile.php" method="POST" enctype="multipart/form-data">
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
            </div>

            <!-- Email (display only, since it cannot be updated) -->
            <div class="form-group">
                <label for="email">Email</label>
                <p><?= htmlspecialchars($admin['email']) ?></p>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password">
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="submit-btn">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>

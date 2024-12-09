<?php
session_start();
require 'db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to edit your profile.");
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // 'student' or 'teacher'

// Fetch user details
if ($userRole == 'student') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
}
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle form submission to update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = $user['email']; // Preserve the current email from the database
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $mobile = trim($_POST['mobile']);
    $profilePicture = $user['profile_picture'] ?? 'uploads/Temp-user-face.jpg';

    $errors = [];

    // Validate unique email
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM students WHERE email = ? AND student_id != ? 
        UNION 
        SELECT COUNT(*) FROM teachers WHERE email = ? AND teacher_id != ? 
    ");
    $stmt->execute([$email, $userId, $email, $userId]);
    $emailExists = $stmt->fetchColumn() > 0;

    if ($emailExists) {
        $errors[] = "This email is already in use.";
    }

    // Validate unique username
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM students WHERE username = ? AND student_id != ? 
        UNION 
        SELECT COUNT(*) FROM teachers WHERE username = ? AND teacher_id != ? 
    ");
    $stmt->execute([$username, $userId, $username, $userId]);
    $usernameExists = $stmt->fetchColumn() > 0;

    if ($usernameExists) {
        $errors[] = "This username is already in use.";
    }

    if (!empty($password) && $password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Handle profile picture upload or deletion
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);
        $profilePicture = $targetFilePath;
    } elseif (isset($_POST['delete_picture']) && $_POST['delete_picture'] == '1') {
        $profilePicture = 'uploads/Temp-user-face.jpg'; // Reset to default image
    }

    if (empty($errors)) {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

        if ($userRole == 'student') {
            $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, username = ?, password = ?, mobile = ?, profile_picture = ? WHERE student_id = ?");
            $stmt->execute([$firstName, $lastName, $username, $hashedPassword, $mobile, $profilePicture, $userId]);
            } else {
                $stmt = $pdo->prepare("UPDATE teachers SET first_name = ?, last_name = ?, username = ?, password = ?, mobile = ?, department = ?, profile_picture = ? WHERE teacher_id = ?");
                $stmt->execute([$firstName, $lastName, $username, $hashedPassword, $mobile, $_POST['department'], $profilePicture, $userId]);
            }

        $_SESSION['profile_update_success'] = "Profile updated successfully!";
        header("Location: profile.php");
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
    <title>Edit Profile</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,600');
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: linear-gradient(#1a73e8, #004db3);
            background-repeat: no-repeat;  /* Ensures the background doesn't repeat */
            background-size: cover;  /* Ensures the gradient covers the full container */
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin: 0px;
        }
        html {
            font-family: 'Montserrat', sans-serif;
            background-image: linear-gradient(#1a73e8, #004db3);
            background-repeat: no-repeat;  /* Ensures the background doesn't repeat */
            background-size: cover;  /* Ensures the gradient covers the full container */
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
            margin: 0 auto; /* Centers the image horizontally */
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
            text-align: center; /* Ensures text is centered */
        }

        .delete-icon:hover {
            background-color: rgba(255, 200, 200, 1);
        }

        .error-message {
            color: #e74c3c;
        }

        /* Responsive Styles */
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
             width: 16px; /* Smaller image */
              height: 16px; /* Smaller image */
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
            
            .delete-icon {
            position: absolute;
            top: 7px;
            right: 110px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            border: solid red;
            padding: 5px;
            cursor: pointer;
            
            }  
            .delete-icon img {
             width: 16px; /* Smaller image */
              height: 16px; /* Smaller image */
               }
        }
    </style>
</head>
<body>
    <div class="edit-profile-container">
        <h1>Edit Your Profile</h1>
        <?php if (isset($_SESSION['profile_update_error'])): ?>
            <p class="error-message"><?= $_SESSION['profile_update_error']; ?></p>
            <?php unset($_SESSION['profile_update_error']); ?>
        <?php endif; ?>

        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                        <!-- Profile Picture Section -->
                        <div class="form-group">
                <div class="profile-img-container">
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?? 'uploads/Temp-user-face.jpg' ?>" id="profilePic" alt="Picture" onclick="document.getElementById('profile_picture').click();">
                    <button type="button" class="delete-icon" onclick="deleteProfilePic()">
                        <img src="uploads/delete.png" alt="Delete Profile Picture" />
                    </button>
                </div>
                <p class="change-avatar-text">Change Avatar</p>
                <input type="file" name="profile_picture" id="profile_picture" style="display:none;" accept="image/*" onchange="document.getElementById('profilePic').src = window.URL.createObjectURL(this.files[0])">
                <input type="hidden" name="delete_picture" id="delete_picture" value="0">
            </div>

            <!-- First Name -->
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
            <label for="email">Email</label>
            <p><?= htmlspecialchars($user['email']) ?></p>
            </div>



            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>
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

            <!-- Mobile -->
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" name="mobile" id="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" required>
            </div>

            <!-- Department (for teachers) -->
            <?php if ($userRole == 'teacher'): ?>
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" name="department" id="department" value="<?= htmlspecialchars($user['department']) ?>" required>
                </div>
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="submit-btn">Update Profile</button>
            </div>
        </form>
    </div>

    <script>
        // Delete profile picture
        function deleteProfilePic() {
            if (confirm("Are you sure you want to delete your profile picture?")) {
                document.getElementById('profilePic').src = 'uploads/Temp-user-face.jpg'; // Reset to default image
                document.getElementById('delete_picture').value = '1'; // Mark for deletion
            }
        }
    </script>
</body>
</html>

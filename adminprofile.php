<?php
session_start();
require 'db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. Please log in to view your profile.");
}

// Fetch logged-in admin's details
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$userId]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Welcome, <?= htmlspecialchars($admin['username']) ?></h1>
            <img src="uploads/admin-default.png" alt="Profile Picture" class="profile-image">
        </div>

        <div class="profile-info">
            <p><img src="https://img.icons8.com/ios-filled/50/000000/username.png" class="icon"><strong>Username:</strong> <?= htmlspecialchars($admin['username']) ?></p>
            <p><img src="https://img.icons8.com/ios-filled/50/000000/email.png" class="icon"><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
            <p><img src="https://img.icons8.com/ios-filled/50/000000/work.png" class="icon"><strong>Role:</strong> <?= htmlspecialchars($admin['role']) ?></p>
            <p><img src="https://img.icons8.com/ios-filled/50/000000/calendar.png" class="icon"><strong>Created At:</strong> <?= htmlspecialchars($admin['created_at']) ?></p>
            <a href="adminedit_profile.php" class="button edit-btn">Edit Profile</a>
        </div>

        <a href="admin-dashboard.php" class="button back-home-btn">Back to Home</a>
    </div>

<style>
/* Same CSS styles as provided */
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,600');
body {
    font-family: 'Montserrat', sans-serif;
    background-image: linear-gradient(#1a73e8, #004db3);
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 70%;
    margin: 15px;
    padding: 0;
}

html{
    font-family: 'Montserrat', sans-serif;
    background-image: linear-gradient(#1a73e8, #004db3);
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 100%;
    margin: 0;
    padding: 0;

}
a {
    color: #046cdb;
    text-decoration: none;
}
.profile-container {
    background-color: #f5fafc;
    padding: 30px;
    max-width: 500px;
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
    text-align: center;
    transition: transform 0.4s ease-in-out;
}
.profile-container:hover {
    transform: scale(1.02);
}
.profile-header h1 {
    font-size: 26px;
    color: #1265b8;
}
.profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid #1265b8;
    object-fit: cover;
}
.profile-info {
    text-align: left;
    margin-top: 20px;
    font-size: 16px;
    color: #618bb8;
}
.profile-info p {
    margin-bottom: 10px;
}
.icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    vertical-align: middle;
}
.button {
    display: inline-block;
    padding: 12px 24px;
    background-color: #618bb8;
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
    cursor: pointer;
    margin-top: 20px;
}
.button:hover {
    background-color: #034f9f;
}
.back-home-btn {
    background-color: #7c8287;
}
.back-home-btn:hover {
    background-color: #42566b;
}

@media (max-width: 768px) {
  body{

    height: 80%;
    width: 130%;



  }
 
  html{
    height: 100%;
  }
  
  .profile-container {
    padding: 2%;
    height: 100%;
    max-width: 100%;

  }

  .profile-header h1 {
    font-size: 22px;
  }
  .button{

    margin-bottom: 4%;
  }
  .profile-image {
    width: 100px;
    height: 100px;
  }
}


</style>
</body>
</html>

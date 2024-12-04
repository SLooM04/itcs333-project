<?php
session_start();
require 'db.php'; // Include the DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Get user details from session
$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // 'student' or 'teacher'

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

$username = $_SESSION['username'] ?? 'User';

// Function to fetch rooms from the database based on department
function fetchRooms()
{
    global $pdo;

        // Fetch all rooms
        $sql = "SELECT * FROM rooms";
        $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Count total bookings
function countTotal($booking){
    $count =0; 
    foreach($booking as $book){
        $count += $book['total_bookings']; 
    }
    return $count;
}

$rooms = fetchRooms(); // Fetch all rooms



try{
$sqlstmt = $pdo->prepare("SELECT room_id, COUNT(*) AS total_bookings FROM bookings GROUP BY room_id");
$sqlstmt->execute();
$bookings_number = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room </title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">

    <style>
        body {
            background-color: #f4f7f6;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .profile {
            text-align: center;
            padding: 20px 10px;
            border-bottom: 1px solid #34495e;
        }
        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile h3 {
            margin: 5px 0;
        }
        .profile p {
            font-size: 14px;
            color: #bdc3c7;
        }
        .menu {
            flex-grow: 1;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .menu li {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .menu li:hover {
            background-color: #34495e;
        }
        .menu li.active {
            background-color: #2980b9;
            font-weight: bold;
        }
        .menu li i {
            margin-right: 10px;
        }

        .Container{
            margin-left: 1rem;
            color: black;
            text-align: center;
        }

        /* h1,h2,h3,p{
            color: black;
        } */

       
        .box {
            background-color: #e4f0f2;
            margin: auto;
            margin-top: 90px;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .room-info{
            background-color: #e4f0f2;
            width: 33%;
            border: 3px solid black;
            border-radius: 5px;
            padding: 30px;
            text-align: left;
            margin-left: 7%;
        }
       
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/Temp-user-face.jpg' ?>" alt="Profile Picture" class="profile-image">
            <span> </span>            
            <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
            <p><?php echo htmlspecialchars($_SESSION['role']); ?></p>
        </div>
            <div class="menu">
                <li><i>📊</i><a href="Reporting.php">Room Statistics</a></li>
                <li class="active"><i>📅</i><a href="Past_bookings.php">Past Bookings</a></li>
                <li><i>📅</i><a href="upcoming_bookings.php">Upcoming Bookings </a></li>
                <li><i>🏠</i><a href="HomeLog.php" class="button back-home-btn">Back to Home</a></li>
            </div>
            


    </div>

    <main class="container">


    </main>
</body>
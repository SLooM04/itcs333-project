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
if($userRole == 'student'){

$sqlstmt = $pdo->prepare("SELECT * FROM bookings WHERE student_id = $userId AND end_time > NOW() AND Status != 'Cancelled' ORDER BY start_time ASC");
$sqlstmt->execute();
$upcoming_bookings = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($upcoming_bookings);
    
    }
else {
    
    $sqlstmt = $pdo->prepare("SELECT * FROM bookings WHERE teacher_id = $userId AND end_time > NOW() AND Status != 'Cancelled' ORDER BY start_time ASC");
    $sqlstmt->execute();
    $upcoming_bookings = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
    $total = count($upcoming_bookings);
        
        }
    }catch(PDOException $e){  
    }
    //if($user['role'] == 'teacher')

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room </title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">

    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
        body {
            background-color: #f4f7f6;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            text-align: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        

    
        .sidebar {
            width: 250px;
            background: linear-gradient(1deg, #1a73e8, #004db3 );  
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
            color: white;
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
            text-align: left;
        }
        .menu li {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .menu li:hover {
            background-color: #34495e;
            text-decoration:none;
        }
        .menu li.active {
            background-color: #2980b9;
            font-weight: bold;
        }
        .menu li i {
            margin-right: 10px;
        }
        .menu a {
            /* padding: 15px 20px; */
            cursor: pointer;
            display: flex;
            align-items: center;
            color: #d0efff;
            text-decoration:none;
        }

        .Container{
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1,h2,h3,p{
            color: black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid black;
        }

        table th, table td {
        padding: 12px;
        text-align: left;
        border: 2px solid black;
        
        }

        table th {
            background-color: #1a2d42;
            color: #ffffff;
            font-weight: bold;
        }
        table tr{
            background-color: white ;
        }

        table tr:nth-child(even) {
            background-color: #d6d6d6;
        }

        table tr:hover {
            background-color: darkgray;
        }

        table td {
            border-bottom: 1px solid #ddd;
            color: black;
        }

        h2 {
            color: #333;
            text-align: center;
            font-size: 24px;
        }

        .cancel{
            color: red;          
            text-decoration: underline;  
            cursor: pointer;       
            font-weight: bold; 
            text-align: center;
        }
        .cancel:hover {
        color: #0056b3;  
        text-decoration: none; 
    }

    
       
       
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'uploads/Temp-user-face.jpg' ?>" alt="Profile Picture" class="profile-image">
            <span> </span>            
            <a href="profile.php"><h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3></a>
            <p><?php echo htmlspecialchars($_SESSION['role']); ?></p>
        </div>
            <div class="menu">
                <!-- <li><i>📊</i><a href="Reporting.php">Room Statistics</a></li> -->
                <li><i>📅</i><a href="Past_bookings.php">Past Bookings</a></li>
                <li  class="active"><i>📅</i><a href="upcoming_bookings.php">Upcoming Bookings </a></li>
                <li><i>🏠</i><a href="HomeLog.php" class="button back-home-btn">Back to Home</a></li>
            </div>
            


    </div>

    <main class="container">

            <header>
                <h1>Upcoming bookings</h1>
            </header>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upcoming_bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                            <td><?= htmlspecialchars($booking['room_name']) ?></td>
                            <td><?= htmlspecialchars($booking['start_time']) ?></td>
                            <td><?= htmlspecialchars($booking['end_time']) ?></td>
                            <td><?= htmlspecialchars($booking['contact_number']) ?></td>
                            <td class="cancel" onclick="confirmCancel(<?= $booking['booking_id']; ?>)">Cancel</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <script>
                function confirmCancel(bookingId) {
                    // Ask for confirmation
                    if (confirm("Are you sure you want to cancel this booking?")) {
                        // Send a request to cancel the booking
                        window.location.href = "cancel_booking.php?booking_id=" + bookingId;
                    }
                }
            </script>

        <!-- <p>
            <?php 
            var_dump($past_bookings);
            ?>
        </p> -->

    </main>
</body>
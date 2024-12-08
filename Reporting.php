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
// Fetch rooms if a department is selected
$rooms = [];
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $rooms = fetchRooms($department); // Fetch rooms by department
} else {
    $rooms = fetchRooms(); // Fetch all rooms
}

try{
$sqlstmt = $pdo->prepare("SELECT 
    (SELECT room_name 
     FROM rooms 
     WHERE rooms.id = bookings.room_id 
     LIMIT 1) AS room_name,
    COUNT(*) AS total_bookings
FROM 
    bookings
GROUP BY 
    bookings.room_id
ORDER BY 
    total_bookings DESC;

");
$sqlstmt->execute();
$bookings_number = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
$totalBookings = countTotal($bookings_number);



}catch(PDOException $e){
    echo "Error: " , $e->getMessage();
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report & Analytics</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">

    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
        body {
            background-color: #c2c3c4;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            
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
            color: #bdc3c7;
            gap: 10px;
        }
         .menu a {
            /* padding: 15px 20px; */
            cursor: pointer;
            display: flex;
            align-items: center;
            color: #d0efff;
        }
        .menu a:hover {
            background-color: #34495e;
        }
        .active {
            background-color: #2980b9;
            font-weight: bold;
        }

        .Container{
            flex:1;
            
            margin-left: 1rem;
            color: black;
            text-align: center;
            background-color: #c2c3c4;
            flex-direction:column;
        }

        .statistics-wrapper {
            display: flex; /* Use Flexbox for horizontal alignment */
            gap: 20px; /* Space between form and top5 section */
            align-items: flex-start; /* Align items to the top */
}

        h1,h2,h3,p{
            color: black;
        }

       
        .Container {
    padding: 20px;
    font-family: 'Poppins', sans-serif;
        }

        /* Flexbox container for layout */
        .layout {
            display: flex;
            justify-content: space-between; /* Space between items */
            align-items: flex-start; /* Align items to the top */
            padding: 20px 0;
        }

        /* Styling for the form box */
        .box {
            flex: 0 0 40%; /* Takes 40% of the width */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Styling for the top5 section */
        .top5 {
            flex: 0 0 20%; /* Takes 20% of the width */
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .top5 h1 {
            font-size: 20px;
            margin-bottom: 15px;
            text-align: center;
        }

        .top5 h2 {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        .top5 h2 span {
            font-weight: bold;
            color: #333;
        }

        /* Responsive behavior for smaller screens */
        @media (max-width: 768px) {
            .layout {
                flex-direction: column; /* Stacks items vertically */
                align-items: center;
            }

            .box,
            .top5 {
                flex: 1 0 auto; /* Items take full width */
                margin-bottom: 20px;
            }
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
        <ul class="menu">
                <li class="active"><i>📊</i>Room Statistics</li>
                <li><i>📅</i><a href="Past_bookings.php">Past Bookings</a></li>
                <li><i>📅</i><a href="upcoming_bookings.php">Upcoming Bookings </a></li>
                <li><i>🏠</i><a href="HomeLog.php" class="button back-home-btn">Back to Home</a></li>
            
            
        </ul>
    </div>

            <main class="Container">
                <h1>Room Statistics</h1>
                <div class="layout">
                    <!-- Room Selection Form -->
                    <form method="POST" action="room_statistics.php" class="box">
                        <h2>Select a Room</h2>
                        <select id="room-dropdown" name="room_id" required>
                            <option value="" disabled selected>Select a room</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= htmlspecialchars($room['id']) ?>">
                                    <?= htmlspecialchars($room['room_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Show Statistics</button>
                    </form>

                    <!-- Top 5 Booked Rooms -->
                    <div class="top5">
                        <h1>Top 5 Booked Rooms</h1>
                        <?php 
                        for($i = 1; $i <= 5; $i++) {
                            if(isset($bookings_number[$i-1])) {
                                echo "<h2>$i. <span>" . htmlspecialchars($bookings_number[$i-1]['room_name']) . "</span> - " 
                                . htmlspecialchars($bookings_number[$i-1]['total_bookings']) . " books</h2>"; 
                            }
                        }
                        ?>
                    </div>
                </div>
            </main>





    
</body>
</html>


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
$roomID = $_GET['id'];
$roomNum = null;
$roomNum_bookings = null;
$lastMonth_roomNum = null;


for ($i =0 ; $i < count($rooms) ; $i++){
if($rooms[$i]['id'] == $roomID){
    $roomNum = $i;
    break;
    }
}


$firstDayOfLastMonth = (new DateTime('first day of last month'))->format('Y-m-d 00:00:00');
$lastDayOfLastMonth = (new DateTime('last day of last month'))->format('Y-m-d 23:59:59');

try{
    
$sqlstmt = $pdo->prepare("SELECT b.room_id, COUNT(*) AS total_bookings, 
(SELECT AVG(c.rating) FROM comments c WHERE c.room_id = b.room_id) AS rating 
    FROM bookings b  WHERE b.status != 'Cancelled' GROUP BY b.room_id;");
$sqlstmt->execute();
$bookings_number = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);


$sqlstmt = $pdo->prepare("SELECT room_id, COUNT(*) AS total_bookings, SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as total_cancelled,
 SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) / 3600 as time
 FROM bookings WHERE start_time BETWEEN :start_date AND :end_date GROUP BY room_id;");
$sqlstmt->execute([':start_date' => $firstDayOfLastMonth, ':end_date' => $lastDayOfLastMonth]);
$lastMonth_number = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){  
}

for ($i =0 ; $i < count($bookings_number) ; $i++){
    if($bookings_number[$i]['room_id'] == $roomID){
        $roomNum_bookings = $i;
        break;
        }
    }

    for ($i =0 ; $i < count($lastMonth_number) ; $i++){
        if($lastMonth_number[$i]['room_id'] == $roomID){
            $lastMonth_roomNum = $i;
            break;
            }
        }

$sqlstmt = $pdo->prepare("SELECT 
    (SELECT room_name 
     FROM rooms 
     WHERE rooms.id = bookings.room_id 
     LIMIT 1) AS room_name,
    COUNT(*) AS total_bookings
    FROM 
        bookings
    WHERE
        status != 'Cancelled'
    GROUP BY 
        bookings.room_id
    ORDER BY 
        total_bookings DESC;

    ");

$sqlstmt->execute();
$total = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
$totalBookings = countTotal($bookings_number);
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
            white-space: nowrap; 
            overflow-x: auto;    
            
        }
        .sidebar {
            width: 15%;
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
            word-wrap: break-word;
        }
        .menu li {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            word-wrap: break-word; 
            overflow-wrap: break-word; 
            white-space: normal;
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
            background-color: #f4f7f6;
            vertical-align:middle;
            white-space: nowrap;  
            overflow-y: hidden;  
            max-width: 100%; 
            
            
        }
        

        h1,h2,h3, p{
            color: black;
        }

        .title{
            margin-right: 90px;
        }

        .Statistics{
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 20px;
        }
       

        .room-info{
            background-color: #e4f0f2;
            width: 60%;
            min-width:fit-content;
            height: 300px;
            margin-left: auto;
            border: 3px solid black;
            border-radius: 5px;
            padding: 30px;
            text-align: center;
            grid-column: 1 / span 2;
            flex-shrink: 0; 
        }

        .top5 {
            
            width: 20%;
            min-width: fit-content;
            height: 300px;
            padding: 20px;
            margin-left: auto;
            margin-right: 3%;
            background-color: #e4f0f2;
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

        @media (max-width: 850px) {
            .Container {
                display: block; 
                width: 100%;    
                overflow-x: auto; 
                white-space: nowrap; 
                -webkit-overflow-scrolling: touch; 
            }
           

            .Statistics {
                display: flex; 
                flex-wrap: nowrap; 
                justify-content: flex-start; 
            }

            .room-info {
                flex-shrink: 0; 
                width: 60%;     
            }

            .top5 {
                flex-shrink: 0; 
                width: 35%;    
                margin-left: 10px; 
                grid-row: 2;
                

            }
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
                    <!-- <li class="active"><i>📊</i><a href="Reporting.php">Statistics </a></li> -->
                    <li><i>📅</i><a href="Past_bookings.php">Past Bookings</a></li>
                    <li><i>📅</i><a href="upcoming_bookings.php">Upcoming Bookings </a></li>
                    <li><i>🏠</i><a href="HomeLog.php" class="button back-home-btn">Back to Home</a></li>
                </div>
                


        </div>

            <main class="Container">
                <h1 class="title">Room Statistics</h1>
                <div class="Statistics">
                    <div class="room-info">
                        <p> Room name: <?php echo $rooms[$roomNum]['room_name'] ?> <br>
                            Room capacity: <?php echo $rooms[$roomNum]['capacity'] ?> <br>
                            Total bookings: <?php if(isset($bookings_number[$roomNum_bookings])) echo $bookings_number[$roomNum_bookings]['total_bookings']; else echo 0 ?><br>
                            Total bookings last month: <?php if(isset($lastMonth_number[$lastMonth_roomNum])) echo $lastMonth_number[$lastMonth_roomNum]['total_bookings']; else echo 0 ?><br>
                            Utilization last month: <?php if(isset($lastMonth_number[$lastMonth_roomNum])) echo number_format($lastMonth_number[$lastMonth_roomNum]['time'] / 315, 2); else echo 0 ?>% <br>
                            Cancelled books last month: <?php if(isset($lastMonth_number[$lastMonth_roomNum])) echo $lastMonth_number[$lastMonth_roomNum]['total_cancelled']; else echo 0 ?> <br>
                            Ratings: <?php if(isset($bookings_number[$roomNum_bookings])) echo number_format($bookings_number[$roomNum_bookings]['rating'], 1); else echo 'Not rated yet' ?>
                            <?php 
                            // var_dump($lastMonth_number);
                            
                            ?>
                        </p>
                        
                    </div> 
                    <div class="top5">
                            <h1>Top 5 Booked Rooms</h1>
                            <?php 
                            for($i = 1; $i <= 5; $i++) {
                                if(isset($bookings_number[$i-1])) {
                                    echo "<h2>$i. <span>" . htmlspecialchars($total[$i-1]['room_name']) . "</span> - " 
                                    . htmlspecialchars($total[$i-1]['total_bookings']) . " books</h2>"; 
                                }
                            }
                            ?>
                    </div>  
                </div>





                
            
        </main>



    
</body>
</html>


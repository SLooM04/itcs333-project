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
$roomID = $_POST['room_id'];
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
$sqlstmt = $pdo->prepare("SELECT room_id, COUNT(*) AS total_bookings, SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) / 3600 as time FROM bookings GROUP BY room_id");
$sqlstmt->execute();
$bookings_number = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);


$sqlstmt = $pdo->prepare("SELECT room_id, COUNT(*) AS total_bookings, SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as total_cancelled FROM bookings WHERE start_time BETWEEN :start_date AND :end_date GROUP BY room_id;");
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
            height: 130vh;
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
            background-color: #f4f7f6;
        }

        h1,h2,h3, p{
            color: black;
        }

       
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
                <li class="active"><i>📊</i><a href="Reporting.php">Statistics </a></li>
                <li><i>📅</i><a href="Past_bookings.php">Past Bookings</a></li>
                <li><i>📅</i><a href="upcoming_bookings.php">Upcoming Bookings </a></li>
                <li><i>🏠</i><a href="HomeLog.php" class="button back-home-btn">Back to Home</a></li>
            </div>
            


    </div>

        <main class="Container">
            <h1>Room Statistics</h1>
            
             <div class="room-info">
                <p> Room name: <?php echo $rooms[$roomNum]['room_name'] ?> <br>
                    Room capacity: <?php echo $rooms[$roomNum]['capacity'] ?> <br>
                    Total bookings: <?php if(isset($bookings_number[$roomNum_bookings])) echo $bookings_number[$roomNum_bookings]['total_bookings']; else echo 0 ?><br>
                    Total bookings last month: <?php if(isset($lastMonth_number[$lastMonth_roomNum])) echo $lastMonth_number[$lastMonth_roomNum]['total_bookings']; else echo 0 ?><br>
                    Utilization last month: <?php if(isset($bookings_number[$roomNum_bookings])) echo number_format($bookings_number[$roomNum_bookings]['time'] / 315, 2); else echo 0 ?>% <br>
                    Cancelled books last month: <?php if(isset($lastMonth_number[$lastMonth_roomNum])) echo $lastMonth_number[$lastMonth_roomNum]['total_cancelled']; else echo 0 ?> <br>
                    Ratings: 
                    <?php 
                    // var_dump($lastMonth_number);
                    
                    ?>
                </p>
                
             </div>


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



                
            
        </main>



    
</body>
</html>


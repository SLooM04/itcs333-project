<?php
session_start();
require 'db.php'; // Include the DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

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
$roomNum = $_POST['room_id'];

for ($i =0 ; $i < count($rooms) ; $i++){
if($rooms[$i]['id'] === $roomNum)
    $roomNum = $i;
}

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
        }

       
        .box {
           
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .selection h2 {
            margin: 0 0 15px;
            font-size: 18px;
            color: #333;
        }
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
       
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="https://via.placeholder.com/80" alt="Profile Picture">
            <h3>Profile</h3>
            <p>Type of profile</p>
        </div>
        <ul class="menu">
            <li class="active"><i>📊</i> Room Statistics</li>
            <li><i>📅</i> Past Bookings</li>
            <li><i>📅</i> Current Bookings</li>
            <li><i>📅</i> Upcoming Bookings</li>
            
        </ul>
    </div>

        <main class="Container">
            <h1>Room Statistics</h1>
            <!-- <p><?php var_dump($rooms) ?></p> -->
             <div class="room-info">
                <p> Room name: <?php echo $rooms[$roomNum]['room_name'] ?> <br>
                    Room capacity: <?php echo $rooms[$roomNum]['capacity'] ?> <br>
                    Total bookings: <?php echo $bookings_number[$roomNum]['total_bookings'] ?><br>
                    Total bookings last month: <br>
                    Utilization: <?php echo $bookings_number[$roomNum]['total_bookings'] ?> <br>
                    Ratings: 
                </p>
                <!-- <h2><?php   count($rooms) ?></h2> -->
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


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
        $sqlstmt = $pdo->prepare("SELECT * FROM bookings WHERE student_id = $userId AND end_time < NOW() OR student_id = $userId AND status = 'Cancelled' ORDER BY start_time ASC");
        $sqlstmt->execute();
        $past_bookings = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
        $total = count($past_bookings);
    }
    else{
        $sqlstmt = $pdo->prepare("SELECT * FROM bookings WHERE teacher_id = $userId AND end_time < NOW() OR teacher_id = $userId AND status = 'Cancelled' ORDER BY start_time ASC");
        $sqlstmt->execute();
        $past_bookings = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);
        $total = count($past_bookings);
    }
}catch(PDOException $e){  
}

for ($i=0 ; $i < count($past_bookings) ; $i++){
    if($past_bookings[$i]['status'] != "Cancelled")
        $past_bookings[$i]['status'] = "Successful";
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
         /* Importing Google Fonts */
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

         
         html, body {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        body {
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
            display: flex;
            text-align: center;
        }

        body.dark-mode{
            background-color: #2e4156;
        }

        body.dark-mode .sidebar{
            background: linear-gradient(1deg, #172047, #34417d);  
            color: #d1d1d1;
            box-shadow: 0 4px 8px rgba(100, 100, 100, 0.5);
        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode p,
        body.dark-mode a {
            color: white;
        }

        body.dark-mode table th{
            background: linear-gradient(1deg, #172047, #34417d);
        }

        body.dark-mode table tr{
            background-color: #004a68;
        }

        body.dark-mode table tr:nth-child(even) {
            background-color: #005a79;
        }
        body.dark-mode .feedback{
            color: gray;
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
            text-decoration: none;
        }
        .menu li.active {
            background-color: #2980b9;
            font-weight: bold;
        }
        .menu li i {
            margin-right: 10px;
        }

        .menu a {
            text-decoration: none;
            color : #d0efff;
          }


        .Container{
            max-width: 100%;
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
        .feedback{
            text-align: center;
            color: #2d96cf;         
            text-decoration: underline;  
            cursor: pointer;       
            font-weight: bold; 
            
        }
        .feedback:hover{
            text-decoration: none; 
            color: #0056b3; 
        }

        @media(max-width: 768px){
            .container{
                width: 100%;
            }
            .sidebar{
                height: 200vh;
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
                <!-- <li><i>📊</i><a href="Reporting.php">Room Statistics</a></li> -->
                <li class="active"><i>📅</i><a href="Past_bookings.php">Past Bookings</a></li>
                <li><i>📅</i><a href="upcoming_bookings.php">Upcoming Bookings </a></li>
                <li><i>🏠</i><a href="HomeLog.php" class="button back-home-btn">Back to Home</a></li>
            </div>
            


    </div>

    <main class="container">

            <header>
                <h1>Past bookings</h1>
            </header>

            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($past_bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                            <td><?= htmlspecialchars($booking['room_name']) ?></td>
                            <td><?= htmlspecialchars($booking['start_time']) ?></td>
                            <td><?= htmlspecialchars($booking['end_time']) ?></td>
                            <td><?= htmlspecialchars($booking['contact_number']) ?></td>
                            <td><?= htmlspecialchars($booking['status']) ?></td>
                            <td class="feedback"><a href="room_details.php?id=<?php echo $booking['room_id']; ?>#<?php echo $booking['room_id']; ?>" class= "feedback" >Submit</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        

        <!-- <p>
            <?php 
            var_dump($past_bookings);
            ?>
        </p> -->

    </main>

    <script>
        // Handle theme toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        // Check for saved theme in localStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.textContent = 'Light Mode';
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');

            // Update button text and save preference
            if (body.classList.contains('dark-mode')) {
                themeToggle.textContent = 'Light Mode';
                localStorage.setItem('theme', 'dark');
            } else {
                themeToggle.textContent = 'Dark Mode';
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
<?php
session_start();
require 'db.php'; // Include the DB connection file

// Check if the user is logged in and is an admin
if ($_SESSION['role'] != 'admin' && !isset($_SESSION['user_id'])) {
    header("Location: combined_login.php");
    exit();
}

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("You are not logged in or authorized to view this page.");
}

// Fetch admin details from the database based on the session user ID
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the admin exists in the database
if (!$user) {
    die("Admin not found.");
}

// Check if room ID is provided in the URL
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Fetch room details from the database
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->execute(['id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "Room not found.";
        exit();
    }
} else {
    echo "No room selected.";
    exit();
}

// Fetch the room ID from the URL
$room_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Invalid room ID");
}

// Fetch the booking date from the URL or request
$booking_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch already booked time slots for the selected date
$stmt = $pdo->prepare("
    SELECT start_time, end_time 
    FROM bookings 
    WHERE room_id = :room_id 
    AND DATE(start_time) = :booking_date
    AND status != 'Cancelled'
");
$stmt->execute(['room_id' => $room_id, 'booking_date' => $booking_date]);
$booked_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to check if a time slot is booked
function isTimeSlotAvailable($start_time, $end_time, $booked_slots)
{
    foreach ($booked_slots as $slot) {
        if (
            ($start_time >= $slot['start_time'] && $start_time < $slot['end_time']) ||
            ($end_time > $slot['start_time'] && $end_time <= $slot['end_time']) ||
            ($start_time <= $slot['start_time'] && $end_time >= $slot['end_time'])
        ) {
            return false;
        }
    }
    return true;
}

// Generate available time slots for a day (8 AM to 10 PM, 1-hour slots)
function generateAvailableSlots($booked_slots)
{
    $available_slots = [];
    $start_hour = 8;
    $end_hour = 22;

    for ($hour = $start_hour; $hour < $end_hour; $hour++) {
        $start_time = sprintf("%02d:00:00", $hour);
        $end_time = sprintf("%02d:00:00", $hour + 1);

        if (isTimeSlotAvailable($start_time, $end_time, $booked_slots)) {
            $available_slots[] = $start_time . ' - ' . $end_time;
        }
    }

    return $available_slots;
}
//finding the placement of the room in $room array



// Get available slots
$available_slots = generateAvailableSlots($booked_slots);
?>

<?php
// Fetch the room ID from the URL
$room_id = $_GET['id'];

// Fetch room details from the database
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :room_id");
$stmt->execute([':room_id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch comments for the room
$stmt = $pdo->prepare("
    SELECT c.*, 
           CASE 
               WHEN c.user_role = 'student' THEN s.username 
               WHEN c.user_role = 'teacher' THEN t.username 
           END AS username
    FROM comments c
    LEFT JOIN students s ON c.user_id = s.student_id AND c.user_role = 'student'
    LEFT JOIN teachers t ON c.user_id = t.teacher_id AND c.user_role = 'teacher'
    WHERE c.room_id = :room_id 
    ORDER BY c.created_at DESC
");
$stmt->execute([':room_id' => $room_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);



//fetching total count for rooms and ratings
$sqlstmt = $pdo->prepare("SELECT b.room_id, COUNT(*) AS total_bookings, 
(SELECT AVG(c.rating) FROM comments c WHERE c.room_id = b.room_id) AS rating 
    FROM bookings b GROUP BY b.room_id;");
$sqlstmt->execute();
$bookings_number = $sqlstmt->fetchAll(PDO::FETCH_ASSOC);

//getting the array position of this room

$roomNum_bookings = null;
for ($i =0 ; $i < count($bookings_number) ; $i++){
    if($bookings_number[$i]['room_id'] == $room_id){
        $roomNum_bookings = $i;
        break;
        }
    }

// Check if the user has a past booking for the room
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
$current_time = date('Y-m-d H:i:s'); // Current timestamp

$stmt = $pdo->prepare("
    SELECT * FROM bookings 
    WHERE room_id = :room_id AND 
          (student_id = :user_id OR teacher_id = :user_id) AND 
          end_time < :current_time AND 
          status = 'Confirmed'
");
$stmt->execute([
    ':room_id' => $room_id,
    ':user_id' => $user_id,
    ':current_time' => $current_time
]);

$has_past_booking = $stmt->rowCount() > 0;



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');




        /* Dark Mode Styles for Feedback and Comments */

        /* Feedback and Comments Section */
        body.dark-mode .comments-section {
            background-color: #2e4156;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .comments-section h2 {
            color: #1e90ff;
            border-bottom: 2px solid #1e90ff;
            padding-bottom: 5px;
        }

        /* Individual Comment Styling in Dark Mode */
        body.dark-mode .comment {
            background-color: #3a4b61 !important;
            border: 1px solid #555;
            color: #ffffff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .comment strong {
            color: #ffffff;
        }

        body.dark-mode .comment em {
            color: #bbbbbb;
        }


        /* Admin Response Styling in Dark Mode */

        body.dark-mode .admin-response {
    background-color: #324c39 !important; 
    border-left-color: #52c476 !important; 
    color: #d1ffd9 !important; 
}


        .admin-response {
            margin-top: 15px;
            padding: 10px;
            border-left: 4px solid #28a745;
            background-color: #eafbe7;
            border-radius: 5px;
            font-style: italic;
            color: #333;
        }

        body.dark-mode .admin-response {
            background-color: #2b4a3f;
            color: #ffffff;
            font-style: italic;
            padding: 15px;
            margin-top: 15px;
            border-left: 5px solid #28a745;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        
        /* Styling comments section container */
        
        
.comments-section {
    margin-top: 40px;
    padding: 20px;
    background-color: #ffffff; 
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}


.comments-section h2 {
    color: #0056b3; 
    border-bottom: 2px solid #0056b3; 
    padding-bottom: 5px;
    font-family: 'Arial', sans-serif; 
}

.comments-section h3 {
    color: #007bff; 
    font-family: 'Verdana', sans-serif; 
    margin-bottom: 10px;
}

/* Styling for comments */
.comment {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9; 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Username and time styling */
.comment strong {
    color: #333; 
    font-weight: bold;
}

.comment em {
    font-size: 12px;
    color: #888; 
}

/* Admin response styling */
.admin-response {
    margin-top: 15px;
    padding: 10px;
    border-left: 4px solid #28a745; 
    background-color: #eafbe7; 
    border-radius: 5px;
    font-style: italic;
}

/* Star rating display */
.comment .rating {
    margin: 10px 0;
    color: #ffcc00; 
    font-size: 18px;
}

/* Styling for the comment form */
.comment-form textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    resize: none;
    background-color: #f8f8f8; 
    color: #333; 
    font-family: 'Arial', sans-serif; 
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1); 
    transition: background-color 0.3s ease, border-color 0.3s ease; 
}

.comment-form textarea:focus {
    background-color: #ffffff; 
    border-color: #007bff; 
    outline: none; 
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
}

.comment-form {
    margin-top: 30px;
    padding: 20px;
    background-color: #f9f9f9; 
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}


.comment-form button {
    background-color: #007bff; 
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.comment-form button:hover {
    background-color: #0056b3; 
}

/* Star Stayle*/
.star-rating {
    display: flex;
    flex-direction: row-reverse; 
    justify-content: center;
    gap: 5px;
}

.star-rating input {
    display: none; /* Hide the radio buttons */
}

.star-rating label {
    font-size: 30px;
    color: gray; /* Default color for stars */
    cursor: pointer;
    transition: color 0.3s ease;
}

.star-rating input:checked ~ label {
    color: gold; /* Gold color for selected stars */
}

.star-rating input:hover ~ label {
    color: gold; /* Gold on hover */
}

        /* Basic Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        body.dark-mode {
            background-color: #2e4156;
            color: white;
            z-index: 1000000000;

        }

        

        body.dark-mode .dropdown-content a {
            color: #000;
        }

        body.dark-mode .dropdown-content a:hover{
            background-color: #2e4156;
        }

        body.dark-mode .recommendation-card {
            background-color: #2e344e;
        }

        body.dark-mode .recommendations h2 {
            color: white;
        }

        body.dark-mode  .action-buttons a{
            background-color: #0b2445;
        }
        

        body.dark-mode header {
            background: linear-gradient(1deg, #172047, #34417d);  
            color: #d1d1d1;
            box-shadow: 0 4px 8px rgba(100, 100, 100, 0.5);
        }
        
        body.dark-mode .feature-box{

            background-color: #335e96;
        }

        body.dark-mode strong {
          color: white;
        }

        body.dark-mode p {
          color: #cbe2ff;
        }


        body.dark-mode nav a {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-content {
            background-color: #2b2b3b;
        }

        body.dark-mode .dropdown-content a {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-content a {
            color: #edf4fa;
        }

        body.dark-mode footer {
            background: linear-gradient(1deg, #000724, #111d4d);  
            color: #d1d1d1;
        }

        .roomnum {
            color: black;
        }

        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: white;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logo img {
            width: 100px;
            border-radius: 20%;
            transition: transform 0.8s;
        }

        /* Header Styles */
        header {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 10px 30px;
            background: linear-gradient(1deg, #1a73e8, #004db3 );  
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            height: 20px;
            z-index: 1000;
        
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            align-items: center;
        }

        .nav-item {
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            color: white;
            font-size: 1em;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background-color 0.3s, border 0.3s;
            position: relative;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid #ffffff;
            border-radius: 8px;
            text-decoration: none;
            color: white;
        }

       

        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: white;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logo img {
            width: 100px;
            border-radius: 20%;
            transition: transform 0.8s;
        }

        @media (min-width: 801px) and (max-width: 1000px) {
            .logo img {
                width: 3rem;
            }
        }





        /* User Profile Section */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .user-profile span {
            font-size: 1em;
            white-space: nowrap;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            color: #222;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 2000;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #222;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Room Details Layout */
        .room-container {
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info {
            padding: 3%;
        }

        .room-images {
            width: 50%;
            margin-right: 110px;
        }

        .main-image {
            width: 35%;
            height: auto;
            border: 2px solid #1A2D42;
            border-radius: 15px;
            margin: 30px;
        }

        .thumbnail-images {
            display: flex;
            flex-direction: column;
            gap: 20px;
            /* Space between thumbnail images */
        }

        .thumbnail-images img {
            width: 150px;
            height: auto;
            border: 2px solid #ccc;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .thumbnail-images img:hover {
            transform: scale(1.2);
        }

        .room-details {
            width: 50%;
            text-align: left;
        }

        .room-details h2 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #000000;
        }

        .room-details p {
            font-size: 1.1em;
            margin: 5px 0;
            color: #000000;
        }

        .reserve-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #1A2D42;
            color: white;
            border-radius: 15px;
            cursor: pointer;
        }

        .reserve-button:hover {
            background-color: #AAB7B7;
        }

        .containerDee {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .features {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .feature-box {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            flex: 1 1 calc(25% - 20px);
            min-width: 200px;
        }

        .feature-box img {
            width: 50px;
            margin-bottom: 10px;
        }

        .feature-box h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .feature-box p {
            color: #555;
            margin: 0%;
        }

        @media (max-width: 768px) {
            .feature-box {
                flex: 1 1 calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .feature-box {
                flex: 1 1 100%;
            }
        }

        /* Footer Styles */
        footer {
            color: white;
            background-color: #1a73e8;
            text-align: center;
            padding: 1rem;
            margin-top: 9rem;
            font-size: 0.9rem;
        }

        footer .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        footer .footer-section {
            flex: 1 1 200px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: left;
        }

        footer .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #ffffff;
            font-weight: 600;
        }

        footer .footer-section ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        footer .footer-section ul li a:hover {
            text-decoration: underline;
        }

        /* Media Queries */
        @media (min-width: 801px) and (max-width: 1000px) {
            .logo img {
                width: 3rem;
            }
        }

        @media (max-width: 768px) {
            footer .footer-container {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-section {
                margin-bottom: 1.5rem;
                text-align: center;
            }

            footer .footer-section ul li {
                margin: 0.2rem 0;
            }
        }

        @media (min-width: 600px) and (max-width: 1024px) {
            .room-container {
                flex-direction: row;
            }

            .room-images,
            .room-details {
                width: 45%;
            }

            .thumbnail-images img {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 599px) {
            header {
                flex-direction: column;
            }

            nav {
                flex-direction: column;
                gap: 5px;
            }

            .room-images,
            .room-details {
                width: 100%;
            }

            .info {
                display: grid;
            }

            .thumbnail-images img {
                width: 60px;
                height: 60px;
            }

            .reserve-button {
                width: 100%;
            }
        }

        /* Responsive design for the footer */
        @media (max-width: 768px) {
            footer .footer-container {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-section {
                margin-bottom: 1.5rem; 
                text-align: center;
            }

            footer .footer-section ul li {
                margin: 0.2rem 0;
            }}

            @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .action-buttons .action-card {
                font-size: 1rem;
                /* Consistent text size */
                padding: 12px;
                /* Adjusted padding */

            }

            .action-buttons a {
                padding: 80px;
            }



            
        }

        @media (max-width: 800px) {
            header {
                display: flex;
                justify-content: space-evenly;
                height: auto;
                flex-direction: row;
                font-size: 0.8rem;
            }

            .logo{
                size: 0.8rem;
            }
       
           

        .nav-item {
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            gap: 40px;
            color: white;
            font-size: 0.8em;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background-color 0.3s, border 0.3s;
            position: relative;
        }
            
            .nav-item:hover,
        .nav-item.active {
            border: none;
            border-radius: 8px;
            box-sizing: content-box;
            
        }

            .logo img {
                width: 40px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .user-profile {
                flex-direction: column;
                align-items: center;
            }

            .action-buttons .action-card {
                font-size: 0.9rem;
                padding: 10px;
            }
        }

        
 /* From Uiverse.io by Galahhad */ 
 .theme-switch {
  --toggle-size: 10px;
  /* the size is adjusted using font-size,
     this is not transform scale,
     so you can choose any size */
  --container-width: 5.625em;
  --container-height: 2.5em;
  --container-radius: 6.25em;
  /* radius 0 - minecraft mode :) */
  --container-light-bg: #3D7EAE;
  --container-night-bg: #1D1F2C;
  --circle-container-diameter: 3.375em;
  --sun-moon-diameter: 2.125em;
  --sun-bg: #ECCA2F;
  --moon-bg: #C4C9D1;
  --spot-color: #959DB1;
  --circle-container-offset: calc((var(--circle-container-diameter) - var(--container-height)) / 2 * -1);
  --stars-color: #fff;
  --clouds-color: #F3FDFF;
  --back-clouds-color: #AACADF;
  --transition: .5s cubic-bezier(0, -0.02, 0.4, 1.25);
  --circle-transition: .3s cubic-bezier(0, -0.02, 0.35, 1.17);
}

.theme-switch, .theme-switch *, .theme-switch *::before, .theme-switch *::after {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-size: var(--toggle-size);
}

.theme-switch__container {
  width: var(--container-width);
  height: var(--container-height);
  background-color: var(--container-light-bg);
  border-radius: var(--container-radius);
  overflow: hidden;
  cursor: pointer;
  -webkit-box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
  box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
  -webkit-transition: var(--transition);
  -o-transition: var(--transition);
  transition: var(--transition);
  position: relative;
}

.theme-switch__container::before {
  content: "";
  position: absolute;
  z-index: 1;
  inset: 0;
  -webkit-box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
  box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
  border-radius: var(--container-radius)
}

.theme-switch__checkbox {
  display: none;
}

.theme-switch__circle-container {
  width: var(--circle-container-diameter);
  height: var(--circle-container-diameter);
  background-color: rgba(255, 255, 255, 0.1);
  position: absolute;
  left: var(--circle-container-offset);
  top: var(--circle-container-offset);
  border-radius: var(--container-radius);
  -webkit-box-shadow: inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), 0 0 0 0.625em rgba(255, 255, 255, 0.1), 0 0 0 1.25em rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), 0 0 0 0.625em rgba(255, 255, 255, 0.1), 0 0 0 1.25em rgba(255, 255, 255, 0.1);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-transition: var(--circle-transition);
  -o-transition: var(--circle-transition);
  transition: var(--circle-transition);
  pointer-events: none;
}

.theme-switch__sun-moon-container {
  pointer-events: auto;
  position: relative;
  z-index: 2;
  width: var(--sun-moon-diameter);
  height: var(--sun-moon-diameter);
  margin: auto;
  border-radius: var(--container-radius);
  background-color: var(--sun-bg);
  -webkit-box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
  box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
  -webkit-filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
  filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
  overflow: hidden;
  -webkit-transition: var(--transition);
  -o-transition: var(--transition);
  transition: var(--transition);
}

.theme-switch__moon {
  -webkit-transform: translateX(100%);
  -ms-transform: translateX(100%);
  transform: translateX(100%);
  width: 100%;
  height: 100%;
  background-color: var(--moon-bg);
  border-radius: inherit;
  -webkit-box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
  box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
  -webkit-transition: var(--transition);
  -o-transition: var(--transition);
  transition: var(--transition);
  position: relative;
}

.theme-switch__spot {
  position: absolute;
  top: 0.75em;
  left: 0.312em;
  width: 0.75em;
  height: 0.75em;
  border-radius: var(--container-radius);
  background-color: var(--spot-color);
  -webkit-box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
  box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
}

.theme-switch__spot:nth-of-type(2) {
  width: 0.375em;
  height: 0.375em;
  top: 0.937em;
  left: 1.375em;
}

.theme-switch__spot:nth-last-of-type(3) {
  width: 0.25em;
  height: 0.25em;
  top: 0.312em;
  left: 0.812em;
}

.theme-switch__clouds {
  width: 1.25em;
  height: 1.25em;
  background-color: var(--clouds-color);
  border-radius: var(--container-radius);
  position: absolute;
  bottom: -0.625em;
  left: 0.312em;
  -webkit-box-shadow: 0.937em 0.312em var(--clouds-color), -0.312em -0.312em var(--back-clouds-color), 1.437em 0.375em var(--clouds-color), 0.5em -0.125em var(--back-clouds-color), 2.187em 0 var(--clouds-color), 1.25em -0.062em var(--back-clouds-color), 2.937em 0.312em var(--clouds-color), 2em -0.312em var(--back-clouds-color), 3.625em -0.062em var(--clouds-color), 2.625em 0em var(--back-clouds-color), 4.5em -0.312em var(--clouds-color), 3.375em -0.437em var(--back-clouds-color), 4.625em -1.75em 0 0.437em var(--clouds-color), 4em -0.625em var(--back-clouds-color), 4.125em -2.125em 0 0.437em var(--back-clouds-color);
  box-shadow: 0.937em 0.312em var(--clouds-color), -0.312em -0.312em var(--back-clouds-color), 1.437em 0.375em var(--clouds-color), 0.5em -0.125em var(--back-clouds-color), 2.187em 0 var(--clouds-color), 1.25em -0.062em var(--back-clouds-color), 2.937em 0.312em var(--clouds-color), 2em -0.312em var(--back-clouds-color), 3.625em -0.062em var(--clouds-color), 2.625em 0em var(--back-clouds-color), 4.5em -0.312em var(--clouds-color), 3.375em -0.437em var(--back-clouds-color), 4.625em -1.75em 0 0.437em var(--clouds-color), 4em -0.625em var(--back-clouds-color), 4.125em -2.125em 0 0.437em var(--back-clouds-color);
  -webkit-transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
  -o-transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
  transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
}

.theme-switch__stars-container {
  position: absolute;
  color: var(--stars-color);
  top: -100%;
  left: 0.312em;
  width: 2.75em;
  height: auto;
  -webkit-transition: var(--transition);
  -o-transition: var(--transition);
  transition: var(--transition);
}

/* actions */

.theme-switch__checkbox:checked + .theme-switch__container {
  background-color: var(--container-night-bg);
}

.theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container {
  left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter));
}

.theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container:hover {
  left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter) - 0.187em)
}

.theme-switch__circle-container:hover {
  left: calc(var(--circle-container-offset) + 0.187em);
}

.theme-switch__checkbox:checked + .theme-switch__container .theme-switch__moon {
  -webkit-transform: translate(0);
  -ms-transform: translate(0);
  transform: translate(0);
}

.theme-switch__checkbox:checked + .theme-switch__container .theme-switch__clouds {
  bottom: -4.062em;
}

.theme-switch__checkbox:checked + .theme-switch__container .theme-switch__stars-container {
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}
    </style>
</head>

<body>
<header>
        <!-- Logo Section -->
        <a  class="logo">
            <img src="uploads/UOB-Colleges-new-logo.png" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->

        <nav class="nav-links">
            <a href="adminrooms.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'adminrooms.php' ? 'active' : ''; ?>">Rooms</a>
            <a href="admin-dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php' ? 'active' : ''; ?>">Home</a>
            <a href="Feedback_Managment.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'Feedback_Managment.php' ? 'active' : ''; ?>">feedbacks</a>
            <a href="admin-support-page.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin-support-page.php' ? 'active' : ''; ?>">Support</a>
        </nav>



        <!-- User Profile Section -->
        <div class="user-profile dropdown">
            <img src="<?= !empty($admin['profile_picture']) ? htmlspecialchars($admin['profile_picture']) : 'uploads/admin-default.png' ?>" alt="Profile Picture" class="profile-image">
            <span> <?php echo htmlspecialchars($user['username']); ?></span>
            <div class="dropdown-content">
                <a href="adminprofile.php">My Profile</a>
                <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
            </div>
        </div>
    </header>
    <main>


        <div class="room-container">
            <!-- Main Room Image -->
            <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" class="main-image" id="main-image">

            <!-- Thumbnail Images -->
            <div class="thumbnail-images">
                <!-- First thumbnail is the main image -->
                <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="Main Image" onclick="changeMainImage('<?php echo htmlspecialchars($room['image']); ?>')">

                <?php
                // Display additional thumbnail images if available
                for ($i = 1; $i <= 4; $i++) {
                    $thumb = $room['thumbnail_' . $i] ?? 'default-thumbnail.jpg'; // Default if no thumbnail
                    if ($i > 1) { // Skip the first one since it's already the main image
                        echo '<img src="RoomPic/' . htmlspecialchars($thumb) . '" alt="Thumbnail ' . $i . '" onclick="changeMainImage(\'' . htmlspecialchars($thumb) . '\')">';
                    }
                }
                ?>
            </div>
        </div>


        <div class="containerDee">

            <div class="title">
                <p class="roomnum"><strong>Detailed information for </strong><?php echo htmlspecialchars($room['room_name']); ?></p>
            </div>

            <div class="features">
                <div class="feature-box">
                    <div style="font-size: 10px;"><img src="https://cdn.discordapp.com/attachments/695196527794061343/1314363167496994907/OH1BQS1.png?ex=67537f96&is=67522e16&hm=b52fbecaaf93b07a2ece160a8e6504d987e09c954b15c4e948aecd34749f6f7e&" style="width:40px; padding-top: 3px;  "> </div>
                    <h3 style="color: #000000">Room Volume</h3>
                    <p><strong style="color:#1a73e8">Capacity</strong><br>  <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                </div>

                <div class="feature-box">
                    <div style="font-size: 30px;">🏢</div>
                    <h3 style="color: #000000">Location</h3>

                    <p><strong style="color:#1a73e8">Department</strong><br> <?php echo htmlspecialchars($room['department']); ?></p>
                    <p><strong style="color:#1a73e8">Floor</strong> <br><?php echo htmlspecialchars($room['floor']); ?></p>
                </div>

                <div class="feature-box">
                    <div style="font-size: 30px;">🖥️</div>
                    <h3 style="color: #000000">Details</h3>
                    <p><strong style="color:#1a73e8">Room Equipment</strong><br> <?php echo htmlspecialchars($room['equipment']); ?></p>
                </div>



                <div class="feature-box">
                    <div style="font-size: 30px;">📊</div>
                    <h3 style="color: #000000">Analytics</h3>
                    <p>
                        <strong style="color:#1a73e8">Total bookings</strong> <br><?php if(isset($bookings_number[$roomNum_bookings])) echo htmlspecialchars($bookings_number[$roomNum_bookings]['total_bookings']); else echo 0 ?><br> 
                        <strong style="color:#1a73e8">Rating</strong><br> <?php if(isset($bookings_number[$roomNum_bookings])){echo htmlspecialchars($bookings_number[$roomNum_bookings]['rating']);} else {echo 'No ratings';} ?>                     
                    </p>
                </div>
                

            </div>

 <!-- Comment Section -->
 <div class="comments-section">
        <h2>Feedbacks</h2>

       <!-- Fetch and Display Existing Comments -->
<div class="comments-section">
    <?php foreach ($comments as $comment): ?>
        <div class="comment" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <!-- Display Comment -->
            <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
            <p class="rating">
                Rating: 
                <?php
                $filled_stars = $comment['rating'];
                $empty_stars = 5 - $filled_stars;
                echo str_repeat('<span style="color: gold;">★</span>', $filled_stars);
                echo str_repeat('<span style="color: gray;">★</span>', $empty_stars);
                ?>
            </p>
            <p><em>Posted on: <?php echo htmlspecialchars($comment['created_at']); ?></em></p>

            <!-- Display Admin Response (If Available) -->
            <?php if (!empty($comment['admin_response'])): ?>
                <div class="admin-response" style="margin-top: 10px; padding: 10px; border-left: 4px solid #28a745; background-color: #eafbe7; border-radius: 5px;">
                    <strong>Admin Reply:</strong>
                    <p><?php echo nl2br(htmlspecialchars($comment['admin_response'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


        <!-- Display Feedback Form Conditionally -->
        <?php if ($has_past_booking): ?>
            <div class="comment-form">
    <h3>Leave your Feedback</h3>
    <form action="add_comment.php" method="POST">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <textarea name="comment_text" placeholder="Write your Feedback here.." required></textarea>
        
        <!-- Star Rating -->
        <label for="rating">Rating:</label>
        <div class="star-rating">
            <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
            <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
            <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
            <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
            <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
        </div>
        
        <button type="submit">Submit Feedback</button>
    </form>
</div>
        <?php else: ?>
        <?php endif; ?>
    </div>

<script>
    function updateRating(rating) {
        // Display the numeric rating
        document.getElementById("rating-display").textContent = rating;
        
        // Update the selected star rating
        var stars = document.querySelectorAll(".star-rating input");
        stars.forEach(function(star, index) {
            if (index < rating) {
                star.nextElementSibling.style.color = "gold"; // Filled star
            } else {
                star.nextElementSibling.style.color = "gray"; // Empty star
            }
        });
    }
</script>




            <script>
                function changeMainImage(image) {
                    document.getElementById("main-image").src = "RoomPic/" + image;
                }
            </script>

    </main>


    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- University Info -->
            <div class="footer-section">
                <h3>University Info</h3>
                <ul>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="https://www.uob.edu.bh/locations">Campus Locations</a></li>
                    <li><a href="#events">Upcoming Events</a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="https://www.uob.edu.bh/admission-requirements">Join UOB</a></li>
                    <li><a href="https://www.uob.edu.bh/deanship-of-graduate-studies-scientific-research">Research</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li>Email: <a href="mailto:info@university.com">info@university.com</a></li>
                    <li>Phone: +123 456 789</li>
                    <li>Address: Sakhir – Kingdom of Bahrain <br>1017 Road 5418 <br>Zallaq 1054</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p style="color:white;">&copy; <?php echo date("Y"); ?> UOB Rooms Reservation | All rights reserved.</p>
            <p>
                <a href="https://www.uob.edu.bh/privacy-policy" style="color : white;">Privacy Policy | </a>
                <a href="https://www.uob.edu.bh/terms-and-conditions" style="color : white;">Terms of Service</a>
            </p>
        </div>
    </footer>

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

        
        document.addEventListener('DOMContentLoaded', () => {
  const themeSwitch = document.querySelector('.theme-switch__checkbox');

  // Load saved state from localStorage
  const savedState = localStorage.getItem('theme-switch-state');
  if (savedState === 'off') {
    themeSwitch.checked = true; // Reversed: "off" means checkbox is checked
    document.body.classList.add('dark-mode'); // Apply dark mode if reversed
  } else {
    themeSwitch.checked = false;
    document.body.classList.remove('dark-mode');
  }

  // Listen for state change
  themeSwitch.addEventListener('change', () => {
    if (themeSwitch.checked) {
      // Checkbox is checked -> Should turn off
      localStorage.setItem('theme-switch-state', 'off');
      document.body.classList.add('dark-mode'); // Apply dark mode if reversed
    } else {
      // Checkbox is unchecked -> Should turn on
      localStorage.setItem('theme-switch-state', 'on');
      document.body.classList.remove('dark-mode'); // Remove dark mode
    }
  });
});
    </script>

</body>

</html>
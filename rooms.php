<?php
session_start();
require 'db.php'; // Include the DB connection file

if (!isset($_SESSION['user_id'])) {
    $isGuest = true; 
    $username = 'Guest'; 
} else {
    $isGuest = false; 
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role']; 

    if ($userRole == 'student') {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
    }
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $username = $_SESSION['username'] ?? 'User';
}

// Function to fetch rooms from the database based on department
function fetchRooms($department = null)
{
    global $pdo;

    // If department is provided, fetch rooms by department
    if ($department) {
        $sql = "SELECT * FROM rooms WHERE department = :department";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['department' => $department]);
    } else {
        // Fetch all rooms if no department is specified
        $sql = "SELECT * FROM rooms";
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch rooms if a department is selected
$rooms = [];
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $rooms = fetchRooms($department); // Fetch rooms by department
} else {
    $rooms = fetchRooms(); // Fetch all rooms
}
?>


<?php
// Ensure the search form values are provided
if (isset($_GET['room_type']) && isset($_GET['room_number'])) {
    $roomType = $_GET['room_type'];
    $roomNumber = $_GET['room_number'];

    // Prepare the SQL query to search for rooms
    $sql = "SELECT * FROM rooms WHERE room_name LIKE ? LIMIT 1";
    
    // Format the room_name like "Room ###" or "Lab ###"
    $roomSearch = $roomType . ' ' . $roomNumber . '%';

    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$roomSearch]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a room is found, redirect to room_details.php with the id in the URL
    if ($room) {
        header("Location: room_details.php?id=" . $room['id']);
        exit();
    } else {
        echo "No room found matching that criteria.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking System</title>    
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        /* General styles */
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #333;
        }

        body.dark-mode {
            background-color: #2e4156;
            color: white;
            z-index: 1000000000;

        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode p,
        body.dark-mode a {
            color: black;
        }

        body.dark-mode footer {
            background: linear-gradient(1deg, #000724, #111d4d);  
            color: #d1d1d1;
        }

        body.dark-mode .accordion-item {
            background: #2b2b3b;
        }

        body.dark-mode .accordion-button {
            background: #2b2b3b;
            color: white;
            border: white;
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
        body.dark-mode {
            background-color: #2e4156;
            color: white;
        }

        body.dark-mode .dropdown-content a:hover {
            background-color: #2e4156;
        }

        body.dark-mode .dropdown-content a {
            color: #000;
        }

        body.dark-mode .recommendation-card {
            background-color: #2e344e;
        }

        body.dark-mode header {
            background-color: #1a2d42;
            color: #d1d1d1;
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
            background-color: #1a2d42;
            color: #d1d1d1;
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

        .action-buttons a {


            text-decoration: none;
            color: #ffffff;
            font-weight: 500;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #1a73e8;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
            margin: 40px 10px;
        }



        .action-buttons a:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 3px solid #003366;
            animation: glowing 1.5s ease-in-out infinite;
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

        



        /* Container */
        .container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            /* Enable snapping to cards */
            gap: 20px;
            /* Space between cards */
            padding: 20px;
            /* Padding around the container */


        }


        .rooms {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            padding: 10px 10% 10px 10%;
        }



        /* ------------------------------------------------*/
        /* Department Cards */
        .department {
            flex: 0 0 auto;
            /* Prevent shrinking or growing, maintain width */
            scroll-snap-align: center;
            /* Snap card to the center */
            width: 170px;
            height: 350px;
            background-color: #f5f0e1;
            border: 3px solid #333;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
            /* Center each card horizontally */
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
        }

        .department .top-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f5f0e1;
            position: absolute;
            top: 3px;
            left: 50%;
            transform: translateX(-50%);
            border: 2px solid #333;
            z-index: 2;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            /* Add shadow to the circle */
        }

        .department .top-circle::before {
            content: "";
            position: absolute;
            width: 1px;
            height: 45px;
            background-color: #333;
            left: 50%;
            top: 0%;
            transform: translateX(-50%) rotate(45deg);
        }

        .department .top-circle::after {
            content: "";
            position: absolute;
            width: 1px;
            height: 45px;
            background-color: #333;
            left: 50%;
            top: 0%;
            transform: translateX(-50%) rotate(135deg);
        }

        .department .window {
            width: 60%;
            height: 60px;
            background-color: #1893a3;
            border-radius: 50% 50% 0 0;
            margin: 10px auto;
            border: 1px solid #333;
            box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.2);
            /* Subtle inner shadow */
        }

        .department .door-R,
        .department .door-L {
            width: 20%;
            height: 80px;
            background-color: #45a8a7;
            margin: 10px auto;
            position: absolute;
            bottom: -10px;
            transform: translateX(-50%);
            border: 1px solid #333;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            /* Add shadow to doors */
        }

        .department .door-R {
            left: 60%;
            text-align: left;
        }

        .department .door-L {
            left: 40%;
            text-align: right;
        }

        .department .door-R-text,
        .department .door-L-text {
            position: absolute;
            bottom: 20px;
        }

        .department .door-L-text {
            right: 0px;
        }

        .department .side,
        .department .side-right {
            width: 25px;
            background-color: #f5f0e1;
            height: 100%;
            position: absolute;
            top: 0;
            font-size: 11px;
            font-weight: bold;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.3);
            /* Add shadow to sides */
        }

        .department .side-right {
            right: 0;
        }

        .department-text {
            position: absolute;
            bottom: 0px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #704900;
            font-size: 25px;
            z-index: 10;
            font-weight: bold;
            position: relative;
        }

        .department-text::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            /* Lighter background */
            z-index: -1;
            filter: blur(7px);
            border-radius: 8px;
        }

        .department:hover {
            transform: scale(1.05);
            /* Slightly enlarge the card */
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.4);
            /* Add a stronger shadow */
            border-color: #555;
            /* Highlight the border */
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        /* Door Sliding Animation */
        .department:hover .door-R {
            transform: translateX(100%);
            /* Slide the right door outward to the right */
            transition: transform 0.6s ease;
        }

        .department:hover .door-L {
            transform: translateX(-200%);
            /* Slide the left door outward to the left */
            transition: transform 0.6s ease;
        }

        /* Ensure Default State for Doors */
        .department .door-R,
        .department .door-L {
            transform-origin: left center;
            /* Rotate from the edge */
            transition: transform 0.4s ease;
        }

        .department .door-L {
            transform-origin: right center;
            /* Rotate from the edge */
        }

        /* ------------------------------------------------*/

        /* Room Gallery */
        .room-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 50px;
            margin: 0%;
            border-radius: 8px;

        }
        
        


        .room {
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow: hidden;
            transition: box-shadow 0.3s;
            margin: 0;
            max-width: 200px;
            /* Set a fixed width for smaller boxes */
            padding: 10px;
            /* Add some padding */

        }

        .room a {
            text-decoration: none;
        }

        .room:hover {
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .room figure {
            margin: 0;

        }

        .room img {
            width: 100%;
            /* Makes the image fill the box */
            height: 150px;
            /* Set a fixed height */
            object-fit: contain;
           
        }

        .room figcaption {
            padding: 1px;
            text-align: left;
        }

        .room h2 {
            font-size: 1.2em;
            margin-bottom: 8px;
            color: #000;
        }

        .room p {
            margin: 5px 0;
            color: #000;
            font-size: 0.9em;
        }


        @keyframes glowing {
            0% {
                border-color: #222;
                box-shadow: 0 0 5px #003366, 0 0 10px #003366, 0 0 15px #003366;
            }

            50% {
                border-color: #222;
                box-shadow: 0 0 10px #222, 0 0 20px #222, 0 0 30px #222;
            }

            100% {
                border-color: darkslategray;
                box-shadow: 0 0 5px #003366, 0 0 10px #003366, 0 0 15px #003366;
            }
        }

    .toggle-buttons {
        text-align: center;
        margin-bottom: 20px;
    }

    .toggle-buttons button {
        padding: 10px 20px;
        margin: 5px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #618bb8;
        color: white;
    }

    .toggle-buttons button:hover {
        background-color: #396391;
    }




        /* Footer styles */
        footer {
            color: white;
            background: linear-gradient(1deg, #024ba9, #96c3ff);  
            text-align: center;
            padding: 1rem 1rem;
            margin-top: 0rem;
            font-size: 0.9rem;
            z-index: 1;
        }

        footer .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            z-index: 1;
        }

        footer .footer-section {
            flex: 1 1 200px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: left;
            z-index: 1;
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

      

            .all {
         text-align: center;
         vertical-align: middle;
         line-height: 0.5; /* Adjust line spacing if needed */
         font-size: 30px; /* Adjust text size */
         color: #333; /* Text color */
         font-weight: bold; /* Make it bold */
         font-family: 'Arial', sans-serif; /* Font family */
         padding: 10px; /* Space inside the element */
         margin: 1px auto; /* Center the div horizontally (if block-level) */
         border: 1px solid #ccc; /* Optional border */
         background: linear-gradient(135deg, #a9c9ff, #d0e6ff, #e3f2fd, #b3d4fc);
         border-radius: 338px; /* Rounded corners */
         max-width: 600px; /* Limit width */
         display: flex; /* Enable flexbox */
         align-items: center; /* Align items vertically in the center */
         justify-content: center; /* Center content horizontally */
         gap: 10px; /* Space between .all and .down */
         height: 80px;

        }
        .down {
         width: 80px; /* Adjust width */
         height: auto; /* Maintain aspect ratio */
         display: block; /* To center, if needed */
         margin: 0 auto; /* Center horizontally */
         border-radius: 8px; /* Rounded corners (optional) */
       }

       .search {
        display: flex;
    justify-content: center;
    align-items: center;
    height: 24vh; /* Full viewport height to center vertically */
    text-align: center; /* Center text inside if needed */
    }

    
    .search select { width: 280px;

}
.search input { width: 500px;

}

.search button {
     width: 140px;
     padding-left: 20px;


}

/* Styles for tablets (between 600px and 1024px) */
@media (max-width: 1024px) {
    .search select, .search input {
        width: 220px; /* Adjust width for tablet screens */
    }

    .search button {
        width: 120px; /* Slightly smaller button on tablets */
    }

    .all {
        font-size: 24px; /* Smaller font size for tablets */
        height: 70px; /* Adjust height for tablets */
        padding: 8px; /* Reduced padding */
    }

    .room-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 50px;
            margin: 0%;
            border-radius: 8px;

        }
}

/* Styles for mobile devices (max-width: 600px) */
@media (max-width: 600px) {
    .search {
        flex-direction: column; /* Stack the elements vertically on mobile */
        height: auto; /* Remove height limitation for mobile */
        padding: 20px; /* Add some padding for mobile */
    }

    .search select, .search input {
        width: 100%; /* Full width for input fields on mobile */
        margin-bottom: 10px; /* Space between fields */
    }

    .search button {
        width: 100%; /* Full width button on mobile */
        padding-left: 0; /* Remove extra padding on mobile */
    }
    
    .all {
        font-size: 14px; /* Smaller font size for mobile */
        height: 60px; /* Adjust height for mobile */
        padding: 5px; /* Reduced padding */
        max-width: 90%; /* Allow more width for smaller screens */

    }
    .down {
        width:40px;
    }

    .room-gallery {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 50px;
            margin: 0%;
            border-radius: 8px;

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

  /* From Uiverse.io by laminelawal */ 

  .inputS input {
    width: 500px;
    padding: 15px;
    font-size: 1em;
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    color: #333;
    transition: all 0.3s;
  }

  .inputS input:focus {
    border-color: #0061f2;
    background-color: #f1faff;
    outline: none;
  }
    </style>
</head>

<body>
    
    <header>
        <!-- Logo Section -->
        <a href="homelog.php" class="logo">
            <img src="uploads/UOB-Colleges-new-logo.png" alt="Logo">
            UOB
        </a>

        <!-- Navigation Links -->

        <nav class="nav-links">
            <a href="homelog.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'homelog.php' ? 'active' : ''; ?>">Home</a>
            <a href="rooms.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>">Rooms</a>
            <a href="reporting.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'active' : ''; ?>">My Reservations</a>
            <a href="supportFAQ.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'support.php' ? 'active' : ''; ?>">Support</a>
        </nav>



       <!-- User Profile Section -->
<div class="user-profile dropdown">
    <img src="<?= isset($_SESSION['user_id']) && !empty($user['profile_picture']) 
        ? htmlspecialchars($user['profile_picture']) 
        : 'uploads/Temp-user-face.jpg'; ?>" 
        alt="Profile Picture" 
        class="profile-image">
    <span><?= isset($_SESSION['username']) 
        ? htmlspecialchars($_SESSION['username']) 
        : 'Guest'; ?></span>

    <div class="dropdown-content">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php">My Profile</a>
            <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
            <label class="theme-switch">
  <input id="themeToggle" type="checkbox" class="theme-switch__checkbox">
  <div class="theme-switch__container">
    <div class="theme-switch__clouds"></div>
    <div class="theme-switch__stars-container">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path>
      </svg>
    </div>
    <div class="theme-switch__circle-container">
      <div class="theme-switch__sun-moon-container">
        <div class="theme-switch__moon">
          <div class="theme-switch__spot"></div>
          <div class="theme-switch__spot"></div>
          <div class="theme-switch__spot"></div>
        </div>
      </div>
    </div>
  </div>
</label>           
        <?php else: ?>
            <a href="combined_login.php">Login</a>
            <a href="account_type.php">Register</a>
            <label class="theme-switch">
  <input id="themeToggle" type="checkbox" class="theme-switch__checkbox">
  <div class="theme-switch__container">
    <div class="theme-switch__clouds"></div>
    <div class="theme-switch__stars-container">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path>
      </svg>
    </div>
    <div class="theme-switch__circle-container">
      <div class="theme-switch__sun-moon-container">
        <div class="theme-switch__moon">
          <div class="theme-switch__spot"></div>
          <div class="theme-switch__spot"></div>
          <div class="theme-switch__spot"></div>
        </div>
      </div>
    </div>
  </div>
</label>         <?php endif; ?>
    </div>
</div>
</div>
        </div>
    </header>
    <!-- Department Selection -->
    <div class="container">
        <div class="department" onclick="redirectToPage('IS')">
            
            <div class="roof">|||||||||||||||||||||||||||</div>
            <div class="top-circle"></div>
            <div class="side">S40</div>
            <div class="side-right">S40</div>
            <div class="window"><br></div>
            <div class="window"><br></div>
            <div class="door-L">
                <div class="door-L-text">-</div>
            </div>
            <div class="door-R">
                <div class="door-R-text">-</div>
            </div>
            <div class="department-text">Information Systems</div>
        </div>

        <div class="department" onclick="redirectToPage('CS')">
            <div class="roof">|||||||||||||||||||||||||||</div>
            <div class="top-circle"></div>
            <div class="side">S40</div>
            <div class="side-right">S40</div>
            <div class="window"><br></div>
            <div class="window"><br></div>
            <div class="door-L">
                <div class="door-L-text">-</div>
            </div>
            <div class="door-R">
                <div class="door-R-text">-</div>
            </div>
            <div class="department-text">Computer Science</div>
        </div>

        <div class="department" onclick="redirectToPage('NE')">
            <div class="roof">|||||||||||||||||||||||||||</div>
            <div class="top-circle"></div>
            <div class="side">S40</div>
            <div class="side-right">S40</div>
            <div class="window"><br></div>
            <div class="window"><br></div>
            <div class="door-L">
                <div class="door-L-text">-</div>
            </div>
            <div class="door-R">
                <div class="door-R-text">-</div>
            </div>
            <div class="department-text">Network Engineering</div>
        </div>

    </div>
    <div class="all">
    <img src="uploads/downA.png" alt="down here" class="down">
     All Departments room
     <img src="uploads/downA.png" alt="down here" class="down">
    </div>

    <form action="convert.php" method="GET" class="search">
    <select name="room_type">
        <option value="">Select Room Type</option>
        <option value="Room" <?php echo isset($_GET['room_type']) && $_GET['room_type'] == 'Room' ? 'selected' : ''; ?>>Room</option>
        <option value="Lab" <?php echo isset($_GET['room_type']) && $_GET['room_type'] == 'Lab' ? 'selected' : ''; ?>>Lab</option>
    </select>
    <input class="inputS"  type="text" name="room_number" placeholder="Enter Room Number" value="<?php echo isset($_GET['room_number']) ? htmlspecialchars($_GET['room_number']) : ''; ?>" />
    <button type="submit">Search</button>
  </form>

    <!-- Room Selection (Dynamic Content) -->
    <div id="rooms" class="rooms">

     
        <!-- Map View -->
       <div id="mapclick" class="map" style="display: none;">
        <h4>Map View Content Here</h4>
       </div>

       <!-- All rooms View -->
       <div id="Allrooms" class="room-gallery" >
            <?php if ($rooms): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <a href="room_details.php?id=<?php echo htmlspecialchars($room['id']); ?>">
                            <figure>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                                <?php else: ?>
                                    <img src="RoomPic/jpg" alt="Default Room Image">
                                <?php endif; ?>
                                <figcaption>
                                    <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                                    <p>
                                        <strong><img src="Seats.png" alt="Capacity Icon" style="width: 28px; height: 28px; vertical-align: middle;"> Capacity:</strong>
                                        <?php echo htmlspecialchars($room['capacity']); ?>
                                    </p>
                                    <p>
                                        <strong><img src="de.png" alt="Department Icon" style="width: 24px; height: 28px; vertical-align: middle;"> Department:</strong>
                                        <?php echo htmlspecialchars($room['department']); ?>
                                    </p>
                                </figcaption>

                            </figure>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rooms available for the selected department.</p>
            <?php endif; ?>
        </div>
    </div>
        
    
        <!-- department rooms View -->
        <div id="roomSelection" class="room-gallery" style="display: none;">
            <?php if ($rooms): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <a href="room_details.php?id=<?php echo htmlspecialchars($room['id']); ?>">
                            <figure>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="RoomPic/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                                <?php else: ?>
                                    <img src="RoomPic/jpg" alt="Default Room Image">
                                <?php endif; ?>
                                <figcaption>
                                    <h2><?php echo htmlspecialchars($room['room_name']); ?></h2>
                                    <p>
                                        <strong><img src="Seats.png" alt="Capacity Icon" style="width: 28px; height: 28px; vertical-align: middle;"> Capacity:</strong>
                                        <?php echo htmlspecialchars($room['capacity']); ?>
                                    </p>
                                    <p>
                                        <strong><img src="de.png" alt="Department Icon" style="width: 24px; height: 28px; vertical-align: middle;"> Department:</strong>
                                        <?php echo htmlspecialchars($room['department']); ?>
                                    </p>
                                </figcaption>

                            </figure>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rooms available for the selected department.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- selected department -->
    <script>
        function  showRooms(department) {
            // Fetch rooms data based on the selected department
            window.location.href = '?department =' + department ;
        }
    </script>

 <script>
    function redirectToPage(department) {
        // Redirect based on the department clicked
        if (department === 'IS') {
            window.location.href = "information_system.php";
        } else if (department === 'CS') {
            window.location.href = "computer_science.php";
        } else if (department === 'NE') {
            window.location.href = "network_engineering.php";
        }
    }
 </script>

   <script >
    function showView(viewId) {
        // Hide all views initially
        document.getElementById('Allrooms').style.display = 'none';
        document.getElementById('roomSelection').style.display = 'none';
        document.getElementById('mapclick').style.display = 'none';

        // Show the selected view
        document.getElementById(viewId).style.display = 'block';
    }
   </script>






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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>

    <script>
         document.addEventListener("DOMContentLoaded", () => {
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
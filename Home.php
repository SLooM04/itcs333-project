<?php
session_start();
require 'db.php';
/*
// Get all rooms from the database
$stmt = $pdo->prepare("SELECT * FROM rooms");
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/
// Check if the button is clicked
if (isset($_POST['reserve_now'])) {
    // Add a delay if you want
    sleep(2); // This pauses for 2 seconds

    // Redirect to the desired page
    header("Location: rooms.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms List</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.5.7/css/pico.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        /* Style for the menu bar */
        nav {
            background-color: #003366;
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2em;
            margin: 0 15px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #0055a5;
        }

        /* Ensure main content is above the video */
        main {
            display: grid;
            min-height: 100vh;
            padding: 80px 20px 20px 20px;
            /* Padding to make space for the fixed menu */
            position: relative;
            z-index: 2;
        }

        /* Make sure the video covers the whole background */
        video.background-video {
           
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            object-fit: cover;
            z-index: 10;
   
        }

        /* Footer Styling */
        footer {
            text-align: center;
            font-size: 1em;
            color: #888;
            margin-top: 40px;
            padding: 20px 0;
            background-color: #003366;
            color: white;
            z-index: 10;
            position: relative;
        }

        footer a {
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 0 10px;
            border-radius: 5px;
        }

        footer a:hover {
            background-color: #0055a5;
        }


        /* Style for the Reserve Now button */
        .reserve-now-button {
            display: block;
            margin: 20px auto;
            padding: 15px 30px;


            font-size: 1.2em;
            border-radius: 5px;
            text-align: center;
            width: 200px;
            text-decoration: none;
        }


        button {
            font-size: 17px;
            padding: 0.5em 2em;
            border: transparent;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            background: dodgerblue;
            color: white;
            border-radius: 4px;
        }

        button:hover {
            background: rgb(2, 0, 36);
            background: linear-gradient(90deg, rgba(30, 144, 255, 1) 0%, rgba(0, 212, 255, 1) 100%);
        }

        button:active {
            transform: translate(0em, 0.2em);
        }

        img{
            top: 0;
            left: 0;
            width: auto;
            height: auto;
        }
    </style>
</head>

<body>



    <!-- Navigation Menu -->
    <nav class="menu">
    <a href="Home.php" class="button">Home</a>
    <a href="rooms.php" class="button">Rooms</a>
    <a href="account_type.php" class="button">Login</a>
    <a href="account_type.php" class="button">Register</a>
</nav>


    <!-- Main Content Section -->
    <main>








<section name="vide">


    <!-- Background Video -->
    <video class="background-video" autoplay muted loop>
        <source src="homevid.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

</section>





        <section name="Button">
            <!-- Reserve Now Button Form -->
            <form method="POST" style="position: relative; display: inline-block;">
                <!-- Image -->
                <img src="S44-106.jpg" alt="pic" style="width: 100%; display: block; border-radius: 8px;">
                <!-- Button -->
                <button type="submit" name="reserve_now" class="reserve-now-button"
                style="
                position: absolute; 
                top: 50%; 
                left: 50%; 
                transform: translate(-50%, -50%); 
                color: white; 
                border: none; 
                padding: 10px 20px; 
                border-radius: 5px; 
                font-size: 1em;
                cursor: pointer;">
                    Reserve Now
                </button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> ITCS333 Project | All rights reserved.</p>
        <div>
            <a href="https://www.facebook.com/uobedubh/" target="_blank">Facebook</a>
            <a href="https://x.com/uobedubh" target="_blank">Twitter</a>
            <a href="https://www.instagram.com/uobedubh/" target="_blank">Instagram</a>
        </div>
    </footer>

</body>

</html>
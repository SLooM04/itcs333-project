<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #4682b4;  /* Light blue color */
            font-size: 2.5em;
            margin-bottom: 40px;
        }
        .icons-container {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
        }
        .icon {
            width: 120px;
            height: 120px;
            background-color: #4a90e2;  /* Lighter blue */
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        .icon:hover {
            transform: scale(1.1);
            background-color: #357ab7;  /* Darker blue on hover */
        }
        /* Make icons look good on mobile */
        @media screen and (max-width: 768px) {
            .icons-container {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Cancel Booking</h1>

        <!-- Icons for selecting Room or Person -->
        <div class="icons-container">

            <!-- Icon for Room -->
            <a href="admin-delete-booking.php" class="icon">
                <i class="fas fa-door-open"></i>
            </a>

            <!-- Icon for Person (Student/Teacher) -->
            <a href="admin-cancel-booking-id.php" class="icon">
                <i class="fas fa-user"></i>
            </a>

           

        </div>
    </div>

</body>
</html>

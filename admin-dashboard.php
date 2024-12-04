<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5f7;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .sections {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin: 40px 0;
        }

        .section {
            position: relative;
            width: 250px;
            height: 250px;
            background: #3498db;
            color: white;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .section:hover {
            background: #2980b9;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .section i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .section:hover .options {
            opacity: 1;
            visibility: visible;
        }

        .options {
            position: absolute;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 10px;
            width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            text-align: left;
        }

        .options a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #3498db;
            padding: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease-in-out;
        }

        .options a:hover {
            background: #f3f4f6;
            color: #2980b9;
            border-radius: 5px;
        }

        .options a i {
            margin-right: 8px;
        }

        .logout {
            margin-top: 30px;
            color: #e74c3c;
            font-weight: bold;
            background-color: transparent;
            border: 2px solid #e74c3c;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .logout:hover {
            background-color: #e74c3c;
            color: white;
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            transform: translateY(-3px);
        }

        @media screen and (max-width: 768px) {
            .sections {
                flex-direction: column;
                align-items: center;
            }

            .section {
                width: 100%;
                height: 200px;
            }

            .options {
                width: 90%;
                bottom: -70px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="sections">
            <!-- Room Section -->
            <div class="section">
                <i class="fas fa-door-open"></i>
                <p>Rooms</p>
                <div class="options">
                    <a href="add-room.php"><i class="fas fa-plus"></i> Add Room</a>
                    <a href="edit-room.php"><i class="fas fa-edit"></i> Edit Room</a>
                    <a href="delete-room.php"><i class="fas fa-trash"></i> Delete Room</a>
                </div>
            </div>

            <!-- Student Section -->
            <div class="section">
                <i class="fas fa-user-graduate"></i>
                <p>Students</p>
                <div class="options">
                    <a href="admin-addStudent.php"><i class="fas fa-plus"></i> Add Student</a>
                    <a href="admin-editStudent.php"><i class="fas fa-edit"></i> Edit Student</a>
                    <a href="admin-deleteStudent.php"><i class="fas fa-trash"></i> Delete Student</a>
                </div>
            </div>

            <!-- Teacher Section -->
            <div class="section">
                <i class="fas fa-chalkboard-teacher"></i>
                <p>Teachers</p>
                <div class="options">
                    <a href="admin-addTeacher.php"><i class="fas fa-plus"></i> Add Teacher</a>
                    <a href="admin-editTeacher.php"><i class="fas fa-edit"></i> Edit Teacher</a>
                    <a href="admin-deleteTeacher.php"><i class="fas fa-trash"></i> Delete Teacher</a>
                </div>
            </div>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>

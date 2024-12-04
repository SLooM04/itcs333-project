<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $capacity = $_POST['capacity'] ?? null;
    $available_timeslot = $_POST['available_timeslot'] ?? null;
    $equipment = $_POST['equipment'] ?? null;
    $department = $_POST['department'] ?? null;

    
    $image = $_FILES['image']['name'] ?? null;
    $thumbnail_2 = $_FILES['thumbnail_2']['name'] ?? null;
    $thumbnail_3 = $_FILES['thumbnail_3']['name'] ?? null;
    $thumbnail_4 = $_FILES['thumbnail_4']['name'] ?? null;

  
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
    }
    if ($thumbnail_2) {
        move_uploaded_file($_FILES['thumbnail_2']['tmp_name'], $uploadDir . $thumbnail_2);
    }
    if ($thumbnail_3) {
        move_uploaded_file($_FILES['thumbnail_3']['tmp_name'], $uploadDir . $thumbnail_3);
    }
    if ($thumbnail_4) {
        move_uploaded_file($_FILES['thumbnail_4']['tmp_name'], $uploadDir . $thumbnail_4);
    }

    
    if ($id && $name && $capacity && $available_timeslot && $equipment && $department) {
        $stmt = $pdo->prepare("INSERT INTO rooms (id, room_name, capacity, available_timeslot, equipment, department, image, thumbnail_2, thumbnail_3, thumbnail_4) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $name, $capacity, $available_timeslot, $equipment, $department, $image, $thumbnail_2, $thumbnail_3, $thumbnail_4]);

      
        header('Location: admin_panel.php');
        exit();
    } else {
        echo "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #1a3d7c;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
            color: #555;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="file"],
        form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #1a3d7c;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #134a7f;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Add a New Room</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="id">Room ID:</label>
            <input type="number" id="id" name="id" required>

            <label for="name">Room Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="available_timeslot">Time:</label>
            <input type="text" id="available_timeslot" name="available_timeslot" required>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>

            <label for="equipment">Equipment:</label>
            <input type="text" id="equipment" name="equipment" required>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>

            <label for="image">Image 1:</label>
            <input type="file" id="image" name="image">

            <label for="thumbnail_2">Image 2:</label>
            <input type="file" id="thumbnail_2" name="thumbnail_2">

            <label for="thumbnail_3">Image 3:</label>
            <input type="file" id="thumbnail_3" name="thumbnail_3">

            <label for="thumbnail_4">Image 4:</label>
            <input type="file" id="thumbnail_4" name="thumbnail_4">

            <button type="submit" class="primary">Add Room</button>
        </form>
    </main>
</body>
</html>

<?php
session_start();
require 'db.php'; // Ensure you have the correct database connection in db.php

$message = '';

// Check if room name is posted for search
if (isset($_POST['room_name'])) {
    $room_name = $_POST['room_name'];

    // Prepare query to fetch room details by room name (excluding 'id')
    $stmt = $pdo->prepare("SELECT id, room_name, capacity, equipment, department, floor, image, thumbnail_2, thumbnail_3, thumbnail_4 FROM rooms WHERE room_name = :room_name");
    $stmt->bindParam(':room_name', $room_name, PDO::PARAM_STR);
    $stmt->execute();

    // Check if room is found
    if ($stmt->rowCount() > 0) {
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Room not found.";
    }
}

// Check if the update button is clicked
if (isset($_POST['update']) && isset($room)) {
    // Get updated values
    $room_name = $_POST['room_name'];
    $capacity = $_POST['capacity'];
    $equipment = $_POST['equipment'];
    $department = $_POST['department'];
    $floor = $_POST['floor'];

    // Handle file uploads
    $uploadDir = 'uploads/';
    $uploadedFiles = [];
    $fileFields = ['image', 'thumbnail_2', 'thumbnail_3', 'thumbnail_4'];

    foreach ($fileFields as $file) {
        if (isset($_FILES[$file]) && $_FILES[$file]['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($_FILES[$file]['name']);
            $targetPath = $uploadDir . $fileName;

            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move the file to the directory
            if (move_uploaded_file($_FILES[$file]['tmp_name'], $targetPath)) {
                $uploadedFiles[$file] = $targetPath;
            } else {
                $uploadedFiles[$file] = null; // Failure to upload file
            }
        } else {
            $uploadedFiles[$file] = null; // No file uploaded
        }
    }

    // Prepare update query
    $updateQuery = "UPDATE rooms SET 
                    room_name = :room_name,
                    capacity = :capacity,
                    equipment = :equipment,
                    department = :department,
                    floor = :floor,
                    image = COALESCE(:image, image),
                    thumbnail_2 = COALESCE(:thumbnail_2, thumbnail_2),
                    thumbnail_3 = COALESCE(:thumbnail_3, thumbnail_3),
                    thumbnail_4 = COALESCE(:thumbnail_4, thumbnail_4)
                    WHERE id = :id";
    
    try {
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':room_name', $room_name, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->bindParam(':equipment', $equipment, PDO::PARAM_STR);
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->bindParam(':floor', $floor, PDO::PARAM_STR);
        $stmt->bindParam(':image', $uploadedFiles['image'], PDO::PARAM_STR);
        $stmt->bindParam(':thumbnail_2', $uploadedFiles['thumbnail_2'], PDO::PARAM_STR);
        $stmt->bindParam(':thumbnail_3', $uploadedFiles['thumbnail_3'], PDO::PARAM_STR);
        $stmt->bindParam(':thumbnail_4', $uploadedFiles['thumbnail_4'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $room['id'], PDO::PARAM_INT);

        // Execute the update
        $stmt->execute();

        $message = "Changes updated successfully!";
    } catch (PDOException $e) {
        $message = "Error updating room: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Light blue */
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007BFF; /* Dark blue */
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus {
            border-color: #007BFF;
            outline: none;
        }
        button {
            background: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        .back-button {
            display: block;
            width: 200px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px auto; /* Centers the button horizontally */
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Room</h1>

    <!-- Search form for room -->
    <form method="POST" action="">
        <input type="text" name="room_name" placeholder="Enter Room Name" required>
        <button type="submit">Search Room</button>
    </form>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'updated') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <?php if (isset($room)): ?>
        <!-- Form to edit room details -->
<form method="POST" action="" enctype="multipart/form-data">

<!-- Hidden field to pass Room ID -->
<input type="hidden" name="id" value="<?= $room['id'] ?>">

<!-- Room Name Field -->
<label for="room_name">Room Name:</label>
<input type="text" id="room_name" name="room_name" value="<?= $room['room_name'] ?>" required>

<!-- Capacity Field -->
<label for="capacity">Capacity:</label>
<input type="number" id="capacity" name="capacity" value="<?= $room['capacity'] ?>" required>

<!-- Equipment Field -->
<label for="equipment">Equipment:</label>
<input type="text" id="equipment" name="equipment" value="<?= $room['equipment'] ?>" required>

<!-- Department Field -->
<label for="department">Department:</label>
<input type="text" id="department" name="department" value="<?= $room['department'] ?>" required>

<!-- Floor Field -->
<label for="floor">Floor:</label>
<input type="text" id="floor" name="floor" value="<?= $room['floor'] ?>" required>

<!-- Optional Image Upload Fields -->
<label for="image">Room Image (Optional):</label>
<input type="file" id="image" name="image">

<label for="thumbnail_2">Thumbnail 2 (Optional):</label>
<input type="file" id="thumbnail_2" name="thumbnail_2">

<label for="thumbnail_3">Thumbnail 3 (Optional):</label>
<input type="file" id="thumbnail_3" name="thumbnail_3">

<label for="thumbnail_4">Thumbnail 4 (Optional):</label>
<input type="file" id="thumbnail_4" name="thumbnail_4">

<!-- Submit Button -->
<button type="submit" name="update">Update Room</button>
</form>

    <?php endif; ?>

    <!-- Button to go back to admin-dashboard.php, centered horizontally -->
    <a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>
<?php
session_start();
require 'db.php'; // Database connection file

// Success/error message
$message = '';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check required fields
    $requiredFields = ['room_name', 'capacity', 'equipment', 'department', 'floor'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $message = "The field $field is required.";
            break;
        
        }
        
    }

    // echo var_dump($_POST);

    if (!$message) { // If no error
        // Get values from POST
        $room_name = $_POST['room_name'];
        $capacity = (int)$_POST['capacity'];
        $equipment = $_POST['equipment'];
        $department = $_POST['department'];
        $floor = $_POST['floor'];

        // Handle optional file uploads
        $uploadDir = 'uploads/';
        $uploadedFiles = [];
        $fileFields = ['image', 'thumbnail_2', 'thumbnail_3', 'thumbnail_4'];

        foreach ($fileFields as $file) {
            if (isset($_FILES[$file]) && $_FILES[$file]['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . '_' . basename($_FILES[$file]['name']);
                $targetPath = $uploadDir . $fileName;

                // Create the directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Move the file to the directory
                if (move_uploaded_file($_FILES[$file]['tmp_name'], $targetPath)) {
                    $uploadedFiles[$file] = $targetPath;
                } else {
                    $uploadedFiles[$file] = null; // In case of failure
                }
            } else {
                $uploadedFiles[$file] = null; // No file uploaded
            }
        }

        try {
            // Prepare the insert query
            $query = "INSERT INTO rooms (room_name, capacity, equipment, department, floor, image, thumbnail_2, thumbnail_3, thumbnail_4) 
                      VALUES (:room_name, :capacity, :equipment, :department, :floor, :image, :thumbnail_2, :thumbnail_3, :thumbnail_4)";
            $stmt = $pdo->prepare($query);

            // Bind values to the query
            $stmt->bindParam(':room_name', $room_name, PDO::PARAM_STR);
            $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
            $stmt->bindParam(':equipment', $equipment, PDO::PARAM_STR);
            $stmt->bindParam(':department', $department, PDO::PARAM_STR);
            $stmt->bindParam(':floor', $floor, PDO::PARAM_STR);
            $stmt->bindParam(':image', $uploadedFiles['image'], PDO::PARAM_STR);
            $stmt->bindParam(':thumbnail_2', $uploadedFiles['thumbnail_2'], PDO::PARAM_STR);
            $stmt->bindParam(':thumbnail_3', $uploadedFiles['thumbnail_3'], PDO::PARAM_STR);
            $stmt->bindParam(':thumbnail_4', $uploadedFiles['thumbnail_4'], PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            // Success message
            $message = "Room added successfully!";
        } catch (PDOException $e) {
            // Display error in case of failure
            $message = "Error while adding the room: " . $e->getMessage();
        }
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

        .form-group {
            flex: 1;
            margin-right: 24px;
        }

        .form-group:last-child {
            margin-right: 0;
        }

        .form-group label {
            font-size: 1.1em;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            font-size: 0.9em;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 60%;
            background-color: white;
            color: #333;
            transition: all 0.3s;
            margin-bottom: 15px;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #0061f2;
            background-color: #f1faff;
            outline: none;
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
    <h1>Add New Room</h1>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="room_name" placeholder="Room Name" required>
        <input type="number" name="capacity" placeholder="Capacity" required>
        <input type="text" name="equipment" placeholder="Equipment" required>
        <div id="registration-department" class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="Information Systems">Information Systems</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                    </select>
                </div>
        <div id="registration-level" class="form-group">
                    <label for="year">Level</label>
                    <select id="floor" name="floor" required>
                        <option value="Ground Floor">Ground Floor</option>
                        <option value="First Floor">First Floor</option>
                        <option value="Second Floor">Second Floor</option>
                    </select>
            </div>
        <label>Room Image (Optional):</label>
        <input type="file" name="image">
        <label>Thumbnail 2 (Optional):</label>
        <input type="file" name="thumbnail_2">
        <label>Thumbnail 3 (Optional):</label>
        <input type="file" name="thumbnail_3">
        <label>Thumbnail 4 (Optional):</label>
        <input type="file" name="thumbnail_4">
        <button type="submit">Add Room</button>
    </form>
</div>

<!-- Back to Dashboard button -->
<a href="admin-dashboard.php" class="back-button">Back to Dashboard</a>

</body>
</html>
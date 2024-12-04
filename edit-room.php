<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_room'])) {
   
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    $room = $stmt->fetch();

    if (!$room) {
        $error = "Room with ID $id not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room'])) {
    $id = $_POST['id'];
    $name = $_POST['name'] ?? null;
    $capacity = $_POST['capacity'] ?? null;
    $available_timeslot = $_POST['available_timeslot'] ?? null;
    $equipment = $_POST['equipment'] ?? null;
    $department = $_POST['department'] ?? null;

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $image = $_FILES['image']['name'] ? $uploadDir . $_FILES['image']['name'] : $_POST['existing_image'];
    if ($_FILES['image']['name']) {
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $thumbnail_2 = $_FILES['thumbnail_2']['name'] ? $uploadDir . $_FILES['thumbnail_2']['name'] : $_POST['existing_thumbnail_2'];
    if ($_FILES['thumbnail_2']['name']) {
        move_uploaded_file($_FILES['thumbnail_2']['tmp_name'], $thumbnail_2);
    }

    $thumbnail_3 = $_FILES['thumbnail_3']['name'] ? $uploadDir . $_FILES['thumbnail_3']['name'] : $_POST['existing_thumbnail_3'];
    if ($_FILES['thumbnail_3']['name']) {
        move_uploaded_file($_FILES['thumbnail_3']['tmp_name'], $thumbnail_3);
    }

    $thumbnail_4 = $_FILES['thumbnail_4']['name'] ? $uploadDir . $_FILES['thumbnail_4']['name'] : $_POST['existing_thumbnail_4'];
    if ($_FILES['thumbnail_4']['name']) {
        move_uploaded_file($_FILES['thumbnail_4']['tmp_name'], $thumbnail_4);
    }

  
    $stmt = $pdo->prepare("UPDATE rooms SET room_name = ?, capacity = ?, available_timeslot = ?, equipment = ?, department = ?, image = ?, thumbnail_2 = ?, thumbnail_3 = ?, thumbnail_4 = ? WHERE id = ?");
    $stmt->execute([$name, $capacity, $available_timeslot, $equipment, $department, $image, $thumbnail_2, $thumbnail_3, $thumbnail_4, $id]);

    header('Location: admin_panel.php');
    exit();
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
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #1a3d7c;
            font-size: 2em;
            text-align: center;
        }
        form label {
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #1a3d7c;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #134a7f;
        }
        .error {
            color: #e74c3c;
            text-align: center;
        }
        .success {
            color: #2ecc71;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Edit Room</h1>

        
        <?php if (!isset($room) || !$room): ?>
            <form method="POST">
                <label for="id">Enter Room ID:</label>
                <input type="number" id="id" name="id" required>
                <button type="submit" name="fetch_room">Fetch Room</button>

                <?php if (isset($error)): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        <?php else: ?>
           
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($room['id']) ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($room['image']) ?>">
                <input type="hidden" name="existing_thumbnail_2" value="<?= htmlspecialchars($room['thumbnail_2']) ?>">
                <input type="hidden" name="existing_thumbnail_3" value="<?= htmlspecialchars($room['thumbnail_3']) ?>">
                <input type="hidden" name="existing_thumbnail_4" value="<?= htmlspecialchars($room['thumbnail_4']) ?>">

                <label for="name">Room Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($room['room_name']) ?>" required>

                <label for="available_timeslot">Time Slot:</label>
                <input type="text" id="available_timeslot" name="available_timeslot" value="<?= htmlspecialchars($room['available_timeslot']) ?>" required>

                <label for="capacity">Capacity:</label>
                <input type="number" id="capacity" name="capacity" value="<?= htmlspecialchars($room['capacity']) ?>" required>

                <label for="equipment">Equipment:</label>
                <input type="text" id="equipment" name="equipment" value="<?= htmlspecialchars($room['equipment']) ?>" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?= htmlspecialchars($room['department']) ?>" required>

                <label for="image">Main Image:</label>
                <input type="file" id="image" name="image">

                <label for="thumbnail_2">Thumbnail 2:</label>
                <input type="file" id="thumbnail_2" name="thumbnail_2">

                <label for="thumbnail_3">Thumbnail 3:</label>
                <input type="file" id="thumbnail_3" name="thumbnail_3">

                <label for="thumbnail_4">Thumbnail 4:</label>
                <input type="file" id="thumbnail_4" name="thumbnail_4">

                <button type="submit" name="update_room">Update Room</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>

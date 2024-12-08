<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4a90e2;
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        form label {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }
        form input[type="number"],
        form input[type="text"],
        form input[type="file"],
        button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        form input[type="number"],
        form input[type="text"],
        form input[type="file"] {
            background-color: #f0f2f5;
        }
        button {
            background-color: #4a90e2;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #357ab7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Room</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="id">Room ID:</label>
            <input type="number" id="id" name="id" required>

            <label for="name">Room Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="available_timeslot">Available Time:</label>
            <input type="text" id="available_timeslot" name="available_timeslot" required>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>

            <label for="equipment">Equipment:</label>
            <input type="text" id="equipment" name="equipment" required>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>

            <label for="image">Main Image:</label>
            <input type="file" id="image" name="image">

            <label for="thumbnail_2">Thumbnail 2:</label>
            <input type="file" id="thumbnail_2" name="thumbnail_2">

            <label for="thumbnail_3">Thumbnail 3:</label>
            <input type="file" id="thumbnail_3" name="thumbnail_3">

            <label for="thumbnail_4">Thumbnail 4:</label>
            <input type="file" id="thumbnail_4" name="thumbnail_4">

            <button type="submit">Add Room</button>
        </form>
    </div>
</body>
</html>

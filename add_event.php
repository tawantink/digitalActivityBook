<?php
// รับค่าจาก URL
$date = isset($_GET['date']) ? $_GET['date'] : null;
if (!$date) {
    die("<p style='color: red; font-weight: bold;'>Invalid request! The 'date' parameter is missing.</p>");
}

// รวมไฟล์ config.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';
include 'layout.php';

// ตรวจสอบว่าแบบฟอร์มถูกส่งมา
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input
    $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);
    $event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_STRING);
    $event_descrip = filter_input(INPUT_POST, 'event_descrip', FILTER_SANITIZE_STRING);
    $event_point = filter_input(INPUT_POST, 'event_point', FILTER_VALIDATE_INT);

    if ($event_date && $event_name && $event_descrip && $event_point !== false) {
        // บันทึกข้อมูลลงในตาราง events
        $sql = "INSERT INTO events (event_date, event_name, event_descrip, event_point) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $event_date, $event_name, $event_descrip, $event_point);

        if ($stmt->execute()) {
            $event_id = $stmt->insert_id;
            $create_table_sql = "CREATE TABLE event_participants_$event_id (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                user_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (event_id) REFERENCES events(id)
            )";

            if ($conn->query($create_table_sql) === TRUE) {
                echo "<p style='color: green;'>Event and sub-table for participants created successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error creating sub-table: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color: red;'>Invalid input!</p>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>สร้างงานกิจกรรม</h2>
        <form method="POST" action="save_event.php?date=<?php echo htmlspecialchars($date); ?>">
            <label for="event_date">วันที่ :</label>
            <input type="text" id="event_date" name="event_date" value="<?php echo htmlspecialchars($date); ?>" readonly>
            
            <label for="event_name">ชื่อกิจกรรม :</label>
            <input type="text" id="event_name" name="event_name" required>
            
            <label for="event_descrip">รายละเอียดกิจกรรม :</label>
            <textarea id="event_descrip" name="event_descrip" required></textarea>
            
            <label for="event_point">แต้มกิจกรรม :</label>
            <input type="number" id="event_point" name="event_point" required min="0">
            
            <button type="submit">บันทึกกิจกรรม</button>
        </form>
    </div>
</body>
</html>
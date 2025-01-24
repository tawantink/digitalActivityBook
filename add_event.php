<?php
// รับค่าจาก URL
$date = isset($_GET['date']) ? $_GET['date'] : null;
if (!$date) {
    echo "Invalid request! The 'date' parameter is missing.";
    exit;
} else {
    echo "Date parameter received: " . htmlspecialchars($date) . "<br>";
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
            // ดึง event_id ที่เพิ่งเพิ่ม
            $event_id = $stmt->insert_id;

            // สร้างตาราง sub-table สำหรับผู้เข้าร่วมกิจกรรม
            $create_table_sql = "CREATE TABLE event_participants_$event_id (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                user_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (event_id) REFERENCES events(id)
            )";

            if ($conn->query($create_table_sql) === TRUE) {
                echo "Event and sub-table for participants created successfully!";
                echo "<br><a href='events.php?date=$event_date'><button>Back to Events</button></a>";
            } else {
                echo "Error creating sub-table: " . $conn->error;
                echo "<br><a href='add_event.php?date=$event_date'><button>Try Again</button></a>";
            }
        } else {
            echo "Error: " . $stmt->error;
            echo "<br><a href='add_event.php?date=$event_date'><button>Try Again</button></a>";
        }

        // ปิดการเชื่อมต่อ
        $stmt->close();
    } else {
        echo "Invalid input!";
    }
    $conn->close();
} else {
    echo "Invalid request!";
}
?>

<!-- HTML form for adding a new event -->
<form method="POST" action="save_event.php?date=<?php echo htmlspecialchars($date); ?>">
    <label for="event_date">Event Date:</label>
    <input type="text" id="event_date" name="event_date" value="<?php echo htmlspecialchars($date); ?>" readonly><br>
    <label for="event_name">Event Name:</label>
    <input type="text" id="event_name" name="event_name" required><br>
    <label for="event_descrip">Event Description:</label>
    <textarea id="event_descrip" name="event_descrip" required></textarea><br>
    <label for="event_point">Event Point:</label>
    <input type="number" id="event_point" name="event_point" required><br>
    <button type="submit">Save Event</button>
</form>
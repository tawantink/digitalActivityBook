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

// ตรวจสอบว่าแบบฟอร์มถูกส่งมา
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "Form submitted.<br>";

    // Validate and sanitize input
    $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);
    $event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_STRING);
    $event_descrip = filter_input(INPUT_POST, 'event_descrip', FILTER_SANITIZE_STRING);
    $event_point = filter_input(INPUT_POST, 'event_point', FILTER_VALIDATE_INT);

    echo "Event Date: " . htmlspecialchars($event_date) . "<br>";
    echo "Event Name: " . htmlspecialchars($event_name) . "<br>";
    echo "Event Description: " . htmlspecialchars($event_descrip) . "<br>";
    echo "Event Point: " . htmlspecialchars($event_point) . "<br>";

    if ($event_date && $event_name && $event_descrip && $event_point !== false) {
        // บันทึกข้อมูลลงในตาราง events
        $sql = "INSERT INTO events (event_date, event_name, event_descrip, event_point) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $event_date, $event_name, $event_descrip, $event_point);

        if ($stmt->execute()) {
            echo "Event created successfully!";
            echo "<br><a href='events.php?date=$event_date'><button>Back to Events</button></a>";
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
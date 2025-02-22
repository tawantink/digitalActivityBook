<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/save_event.php
// รับค่าจาก URL
$date = isset($_GET['date']) ? $_GET['date'] : null;
if (!$date) {
    echo "Invalid request! The 'date' parameter is missing.";
    exit;
}

// รวมไฟล์ config.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

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
            // Redirect to events.php after successful insertion
            header("Location: events.php?date=$event_date");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            echo "<a href='add_event.php?date=$event_date' class='btn btn-secondary'>Try Again</a>";
        }

        // ปิดการเชื่อมต่อ
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Invalid input!</div>";
        echo "<a href='add_event.php?date=$event_date' class='btn btn-secondary'>Try Again</a>";
    }
    $conn->close();
} else {
    echo "<div class='alert alert-danger'>Invalid request!</div>";
}
?>
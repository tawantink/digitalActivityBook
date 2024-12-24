<?php
// รวมไฟล์ config.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

// ตรวจสอบว่าแบบฟอร์มถูกส่งมา
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_date = $_POST['event_date'];
    $event_name = $_POST['event_name'];
    $event_descrip = $_POST['event_descrip'];
    $event_point = $_POST['event_point'];

    // บันทึกข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO events (event_date, event_name, event_descrip, event_point) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $event_date, $event_name, $event_descrip, $event_point);

    if ($stmt->execute()) {
        echo "Event added successfully!";
        echo "<br><a href='events.php?date=$event_date'><button>Back to Events</button></a>";
    } else {
        echo "Error: " . $stmt->error;
        echo "<br><a href='add_event.php?date=$event_date'><button>Try Again</button></a>";
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request!";
    exit;
}
?>

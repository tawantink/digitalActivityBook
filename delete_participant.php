<?php
// รวมไฟล์ config.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

// รับค่า event_id และ participant_id จาก URL
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$participant_id = isset($_GET['participant_id']) ? $_GET['participant_id'] : null;

if (!$event_id || !$participant_id) {
    echo "Invalid request!";
    exit;
}

// ระบุชื่อของ sub-table
$participants_table = "event_participants_" . $event_id;

// ลบผู้เข้าร่วมจาก sub-table
$sql = "DELETE FROM $participants_table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $participant_id);

if ($stmt->execute()) {
    echo "Participant removed successfully!";
    echo "<br><a href='event_detail.php?event_id=$event_id'><button>Back to Event</button></a>";
} else {
    echo "Error: " . $stmt->error;
    echo "<br><a href='event_detail.php?event_id=$event_id'><button>Try Again</button></a>";
}

$stmt->close();
$conn->close();
?>

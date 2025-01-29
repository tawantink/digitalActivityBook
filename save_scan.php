<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/save_scan.php
include 'configCon.php';

// รับข้อมูลจากการสแกน QR code
$data = json_decode(file_get_contents('php://input'), true);
$event_id = $data['event_id'];
$username_id = $data['username_id'];

// ตรวจสอบว่ามีข้อมูลในตาราง event_history ที่ตรงกับ event_id และ username_id หรือไม่
$sql = "SELECT * FROM event_history WHERE event_id = ? AND username_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $event_id, $username_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // อัปเดตคอลัมน์ check_status เป็น true
    $sql = "UPDATE event_history SET check_status = 'true' WHERE event_id = ? AND username_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $event_id, $username_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลที่ตรงกัน']);
}

$stmt->close();
$conn->close();
?>
<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/save_scan.php
include 'configCon.php';

// รับข้อมูลจากการสแกน QR code
$data = json_decode(file_get_contents('php://input'), true);
$event_id = $data['event_id'];
$username = $data['username_id'];

// ตรวจสอบว่ามีข้อมูลในตาราง users ที่ตรงกับ username หรือไม่
$sql = "SELECT user_id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];

    // ตรวจสอบว่ามีข้อมูลในตาราง history ที่ตรงกับ event_id และ user_id หรือไม่
    $sql = "SELECT * FROM history WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // อัปเดตคอลัมน์ check_status เป็น 1
        $sql = "UPDATE history SET check_status = 1 WHERE event_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $event_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลการลงทะเบียนของนักศึกษา']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลนักศึกษา']);
}

$stmt->close();
$conn->close();
?>
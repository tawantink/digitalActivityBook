<?php
// รวมไฟล์ configCon.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

// รับค่า id จาก URL
$id = $_GET['id'];

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (!$id) {
    die("ไม่พบ ID กิจกรรม");
}

// ลบข้อมูลกิจกรรมจากฐานข้อมูล
$sql = "DELETE FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "ลบข้อมูลสำเร็จ!";
    header("Location: show_events.php"); // กลับไปหน้าแสดง event
    exit;
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$conn->close();
?>

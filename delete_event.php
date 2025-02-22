<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/delete_event.php
// รวมไฟล์ configCon.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

// รับค่า id จาก URL
$id = $_GET['event_id'];

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (!$id) {
    die("ไม่พบ ID กิจกรรม");
}

// เริ่มการทำธุรกรรม
$conn->begin_transaction();

try {
    // ลบข้อมูลในตาราง history ที่มีกิจกรรมนั้น ๆ
    $sql = "DELETE FROM history WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ลบข้อมูลกิจกรรมจากฐานข้อมูล
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ถ้าทุกอย่างสำเร็จ ให้ commit การทำธุรกรรม
    $conn->commit();
    echo "ลบข้อมูลสำเร็จ!";
    header("Location: show_events.php"); // กลับไปหน้าแสดง event
    exit;
} catch (Exception $e) {
    // ถ้ามีข้อผิดพลาด ให้ rollback การทำธุรกรรม
    $conn->rollback();
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>
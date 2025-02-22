<?php
ob_start(); // เริ่ม output buffering

include 'configCon.php';

// เริ่มการทำธุรกรรม
$conn->begin_transaction();

try {
    // ลบประวัติการเข้าร่วมกิจกรรมทั้งหมด
    $sql = "DELETE FROM history";
    if (!$conn->query($sql)) {
        throw new Exception("Error deleting from history: " . $conn->error);
    }

    // อัปเดตแต้มกิจกรรมของนักศึกษาทุกคนให้เป็น 0
    $sql = "UPDATE users SET points = 0";
    if (!$conn->query($sql)) {
        throw new Exception("Error updating users: " . $conn->error);
    }

    // ถ้าทุกอย่างสำเร็จ ให้ commit การทำธุรกรรม
    $conn->commit();

    // เปลี่ยนเส้นทางกลับไปยังหน้า admin_table_Name.php
    header("Location: admin_table_Name.php");
    exit();
} catch (Exception $e) {
    // ถ้ามีข้อผิดพลาด ให้ rollback การทำธุรกรรม
    $conn->rollback();
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

$conn->close();

ob_end_flush(); // จบ output buffering
?>

<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/save_activity_points.php
include 'configCon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $term = $_POST['term'];
    $year = $_POST['year'];
    $activity_points = $_POST['activity_points'];

    if (!is_numeric($activity_points)) {
        echo "ค่าที่ป้อนไม่ถูกต้อง!";
        exit;
    }

    // อัปเดตค่าในฐานข้อมูล
    $sql = "UPDATE settings SET activity_points = ? WHERE term = ? AND year = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $activity_points, $term, $year);

    if ($stmt->execute()) {
        // เปลี่ยนเส้นทางกลับไปยังหน้า admin_dashboard.php
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>